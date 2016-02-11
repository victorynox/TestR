# êàêèå ñòîëáöû
#   sold:  ItemID
#   publish:  ItemID ProductID price_real shipping_real

# ïàðàìåòðû êîòîðûå íóæíî ïåðåäàâàòü
#   ïîëüçûâàòåëü áàçû äàííûõ myBDUser = "admin";
#   ïàðîëü ê áàçå äàííûõ myBDPassword = "password";
#   myHost = "RDS Host";
#   myDbname = "MyDB";
#	route = "/var/www/html/img/";
#   myProb (int) //change 
#   myCount //cout

	

args <- commandArgs(trailingOnly = T); # Ïîëó÷àåì àðãóìåíòû èç êîìàíäíîé ñòðîêè

{
  FormatDate = "%Y-%m-%d %H:%M";
  myBDUser = args[1];
  myBDPassword = args[2];
  myHost = args[3];
  myDbname = args[4];
  myRoute = "/var/www/TestR/public/csv/";
  myProb = as.integer(args[5]);
  myCount = as.integer(args[6]);
}# Ñ÷èòûâàíèå ïàðàìåòðîâ

{
library(RMySQL)
con <- dbConnect(MySQL(),
                 user = myBDUser,
                 password = myBDPassword,
                 host = myHost,
                 dbname=myDbname);

data.publish <- data.frame(dbGetQuery(conn = con, statement = paste("select ItemID, ProductID, price_real, shipping_real from ", myDbname, ".publish;",sep = "")));
data.sold <-data.frame(dbGetQuery(conn = con, statement = paste("select ItemID from ", myDbname, ".sold;", sep="")));

dbDisconnect(con);

}# ñ÷èòûâàíèå ñ áàçû äàííûõ


{


change.publish <-function(publish=data.publish){
  
  return(publish);
}

change.sold <-function(sold=data.sold){
  
  return(sold);
}

}#ïðîöåäóðû äëÿ îáðàáîòêè ñ÷èòàíûõ òàáëèö


{
data.publish = change.publish();
data.sold = change.sold();
}# âûçîâ ïðîöåäóð îáðàáîòêè ñ÷èòàíûõ òàáëèö


tableProduct <- function(publish = data.publish, 
                         sold = data.sold,
                         delta_prob = myProb){
  
  #òàáëèöà ÷àñòîò ñ ñòîëáöàìè: ProductID, count_sold
  sold_product = data.frame(table(merge(subset(sold, select = c(ItemID)),
                                        subset(publish, select = c(ItemID, ProductID)),
                                        by.x = "ItemID",
                                        by.y = "ItemID")$ProductID));
  names(sold_product) = c("ProductID", "count_sold");
  
  
  #òàáëèöà ÷àñòîò ñ ñòîëáöàìè: ProductID, count_push
  push_product = data.frame(table(publish$ProductID));
  names(push_product) = c("ProductID", "count_push");
  
  #òàáëèöà ÷àñòîò ñ ñòîëáöàìè: ProductID, count_sold, count_push
  table_product = merge(sold_product,
                        push_product,
                        by.x = "ProductID",
                        by.y = "ProductID");
  
  {
    mean_price = data.frame(aggregate(publish$price_real+publish$shipping_real,
                                      by = list(ProductID = publish$ProductID),
                                      mean));
    names(mean_price) = c("ProductID", "price")
    
    table_product = merge(table_product,
                          mean_price,
                          by.x = "ProductID",
                          by.y = "ProductID");
  }#äîáàâëÿåì ñðåäíþþ öåíó òîâàðà
  
  
  
  #äîáàâëÿåì âåðîÿòíîñòü ïðîäàæè(prob)
  table_product = transform(table_product, prob = pmin(1,count_sold/count_push));
  
  #äîáàâëÿåì ñðåäíþþ ïðèáûëü çà ìåñÿö(prof_mounth)
  table_product = transform(table_product, prof_mounth = (prob*price/10-0.05)*count_push/7);
  
  #äîáàâëÿåì âåðîÿòíîñòü ïðè íîâîé ñèñòåìå âûñòàâëåíèÿ(new_prob)
  table_product = transform(table_product, new_prob = pmin(1, count_sold*delta_prob/(count_push+(1-delta_prob)*count_sold)));
  
  #äîáàâëÿåì íîâóþ ñðåäíþþ ïðèáûëü çà ìåñÿö(new_prof_mounth)
  table_product = transform(table_product, new_prof_mounth = (new_prob*price/10-0.15)*(count_push+(1-delta_prob)*count_sold)/7);
  
  #äîáàâëÿåì èçìåíåíèå ñðåäíåé ïðèáûëè çà ìåñÿö(delta_prof_mounth)
  table_product = transform(table_product, delta_prof_mounth = new_prof_mounth - prof_mounth);
  
  #ñîðòèðóåì ïî óáûâàíèþ ðàçíèöè ïðèáûëè
  table_product = table_product[order(-table_product$delta_prof_mounth),];
  
  return(table_product);
}#òàáëèöà ÷àñòîò ïî îòäåëüíûì òîâàðàì



saveTable <- function(table,
                      waySave = myRoute,
                      name,
                      sepSave = "|"){
  
  
  write.table(head(data.frame(table[1],round(table[-1], digits = 3)), myCount),
              file = paste0(waySave, name, ".csv"),
              sep = sepSave,
              row.names = FALSE);
  
}#ôóíêöèÿ äëÿ ñîõðàíåíèÿ òàáëèöû



result = "a";

saveTable(table = tableProduct(), name = result);

result;

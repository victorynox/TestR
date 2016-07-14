define([
    "dijit/Dialog",
    "dijit/form/Form",
    "dijit/form/TextBox",
    "dijit/form/Button",
    "dojo/on",
    "dojo/query",
    "dojo/dom",
    "dojo/_base/array",
    "dojox/validate/web",
    "dojox/validate/us",
    "dojox/validate/check",
    "dojo/domReady!"
], function(Dialog, Form, TextBox, Button, on, query, dom, arrayUtil, validate)
{
    function Fcheck(f){
        var results = validate.check(f, profile);
        var r = dom.byId("result");
        if(results.isSuccessful()) {
            r.innerHTML = "Everything passed validation!";
        } else {
            var s = "";
            var missing = results.getMissing();
            if(missing.length){
                s += '<h4>The following fields are missing:</h4>'
                    + '<ol>';
                arrayUtil.forEach(missing, function(field){
                    s += '<li>' + field + '</li>';
                });
                s += '</ol>';
            }

            var invalid = results.getInvalid();
            if(invalid.length){
                s += '<h4>The following fields are invalid:</h4>'
                    + '<ol>';
                arrayUtil.forEach(invalid, function(field){
                    s += '<li>' + field + '</li>';
                });
                s += '</ol>';
            }

            r.innerHTML = s;
        }
    }
    var profile = {
        //trim: ["login"],
        required: ["login", "email", "pass", "pass2"],
        constraints: {
            login: 	validate.isText,
            pass:  	validate.isText,
            pass2: 	validate.isText,
            email:		[validate.isEmailAddress, false, true]
        },
        confirm: {
            "pass2": "pass"
        }
    };

    var form = new Form({
        id: 'myForm'
    });
    new TextBox({
        title: "Login",
        name: "login"
    }).placeAt(form.containerNode);

    new TextBox({
        title: "Email",
        name: "email",
        id: "email"
    }).placeAt(form.containerNode);
    new TextBox({
        type: 'password',
        title: "password",
        name: "pass"
    }).placeAt(form.containerNode);

    new TextBox({
        type: 'password',
        title: "Confirm password",
        name: "pass2"
    }).placeAt(form.containerNode);

    var submit = new Button({
        value: "OK",
    }).placeAt(form.containerNode);

    var dia = new Dialog({
        content: form,
        title: "Dialog with form",
        style: "width: 300px; height: 300px;"
    });
    form.startup();
    dia.show();
    var handle = on(submit, "click", function(){
        //var a = form.validate();
        var f = query("form")[0];
        Fcheck(f);

    });
    dom.byId("email").validator = function(value, constraints){
        alert('asd');
        return validate.isEmailAddress(value, constraints);
    }
});
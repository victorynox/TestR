require([
	${dependencies}
], function (declare ${callbackParams}) {
	${dataDeclaration}

	// Instantiate grid
	var grid = new (declare([${gridModules}]))(${gridOptions}, 'grid');

	grid.startup();${gridRender}${dataCreation}
});

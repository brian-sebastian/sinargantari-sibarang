let dynamicTable;
// first parameter is type "server-side" or "client-side", the two parameter is custom configuration you can add from argument funtion
function initializeTable(type, config = {}) {
	// The two parameter must be object
	if (typeof config == "object") {
		// check if type server-side then add processing, serverside, responsive properti
		switch (type) {
			case "server-side":
				config.processing = true;
				config.serverSide = true;
				config.responsive = true;
				break;
			// check if type server-side then add, responsive properti
			case "client-side":
				config.responsive = true;
				break;
			default:
				alert("Type not found!");
				return;
				break;
		}

		if ($("#dynamicTable").length > 0) {
			dynamicTable = $("#dynamicTable").DataTable(config);
		}
	} else {
		alert("The two parameter must be object at least");
	}
}

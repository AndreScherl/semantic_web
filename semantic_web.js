//! Falls etwas nicht mehr funktioniert: Habe alle "Y.one("input[name=wwwroot]").get("value")" durch "M.cfg['wwwroot']" ersetzt.

/**
 * helper functions to handle events triggered by user interface
 *
 * @package	Semantic Web
 * @author	Andre Scherl
 * @version	09.11.2011
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 M.block_semantic_web = {
 	Y:null
 };
 
 //! actions triggered by the metadata form of the semantic web block
 M.block_semantic_web.init_metadata_actions = function(Y){
 	this.Y = Y;
 	Y.use('io-form');
 	
 	/**
 	 * functions used by metadata form
 	 */
 	
 	// if the title is changed, the selected learning activity metadata will be shown
 	var changeTitle = function(e){
 		if(Y.one("#id_title").get("type") != "text"){
 			var currenturl = window.location.href.split("?");
    		window.location = currenturl[0]+"?id="+Y.one("#id_title").get("value");
    	}
	};
	Y.on("change", changeTitle, "#id_title");
	
	// add a new relation by clicking the button
	var addRelation = function(){
	    if(Y.one("#id_source").get("value") * Y.one("#id_target").get("value") != 0 && Y.one("#id_relations").get("value") != 0){
	    	// Url bis zum Verzeichnis
	    	var newurl = window.location.href.slice(0, window.location.href.lastIndexOf("/")+1);
	    	// php-Datei anhängen
	    	newurl = newurl + "insert_relation.php?";
	    	// Parameter dazu
	    	newurl = newurl + window.location.href.slice(window.location.href.lastIndexOf("?")+1, window.location.href.length);
	    	newurl = newurl + "&sid="+Y.one("#id_source").get("value");
	    	newurl = newurl + "&tid="+Y.one("#id_target").get("value");
	    	newurl = newurl + "&rel="+Y.one("#id_relations").get("value");
	    	// URL aufrufen
	    	window.location = newurl;
	    }else{
	    	alert("Relation incomplete!");
	    }
	}
	Y.on("click", addRelation, "#id_add_rel");
	
	// disable the chosen source in targets dropdown menu
	var disableSourceInTargets = function(){
	    Y.all(".target").set("disabled", "");
	    Y.one("#id_target_"+Y.one("#id_source").get("value")).set("disabled", "disabled");
	}
	Y.on("change", disableSourceInTargets, "#id_source");
	
	// disable the chosen target in sources dropdown menu
	var disableTargetInSources = function(){
	    if(Y.one("#id_source").get("value") == Y.one("#id_target").get("value")){
	    	Y.one("#id_source").set("value", "0");
	    }
	    Y.all(".source").set("disabled", "");
	    Y.one("#id_source_"+Y.one("#id_target").get("value")).set("disabled", "disabled");
	}
	Y.on("change", disableTargetInSources, "#id_target");
	
	// close window
	var closeWindow = function(){
	    parent.window.scrollTo(0, 0);
	   	var dimmDiv = parent.document.getElementById("id_dimmDiv");
	   	dimmDiv.parentNode.removeChild(dimmDiv);
	   	var metadataframe = parent.document.getElementById("id_metadataframe");
	   	metadataframe.parentNode.removeChild(metadataframe);
	}
	Y.on("click", closeWindow, "#id_cancel");
	
	// save and close
	var saveAndClose = function(){
		var form = document.getElementById('id_metadataform');
				
		var cfg = {
    		method: "POST",
    		form: {
				id: id_metadataform
			}
		};
		
		var handleSuccess = function(){
			parent.window.scrollTo(0, 0);
	   		var dimmDiv = parent.document.getElementById("id_dimmDiv");
	   		dimmDiv.parentNode.removeChild(dimmDiv);
	   		var metadataframe = parent.document.getElementById("id_metadataframe");
	   		metadataframe.parentNode.removeChild(metadataframe);
		}
		
		var handleFailure = function (){
			alert("Failure: no data sent to database");
		}
		
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
		
		var request = Y.io("storeData.php", cfg);
		
	}
	Y.on("click", saveAndClose, "#id_submitclose");
	
	// disable buttons, if no learning activity is chosen
	if(Y.one("#id_title").get("value") == 0) {
		Y.one("#id_submit").set("disabled", "disabled");
		Y.one("#id_submitclose").set("disabled", "disabled");
	}
};

 //! actions triggered by elements of the semantic web configurations form
 M.block_semantic_web.init_configblock_actions = function(Y){
 	this.Y = Y;
 	
	/**
	 *
	 * functions used by block configuration
	 *
	 */
	Y.one("input[name=currenturl]").set("value", window.location); 
	
	var storeWebPrefs = function() {
		Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/store_web_prefs.php");
    	var mform1 = Y.one("#mform1");
    	mform1.submit();
	}
	Y.on("change", storeWebPrefs, "#id_depth");
	Y.on("click", storeWebPrefs, "#id_adaption_checkbox");
	Y.on("click", storeWebPrefs, "#id_case_collection_checkbox");
	Y.on("click", storeWebPrefs, "#id_web_animation_checkbox");
	
	/**
	 *
	 * bundle configuration
	 *
	 */
	 
	 Y.one("#id_new_bundle_table").setStyle("display", "none");
	 
	 if(Y.one("input[name=bundleId]").get("value") != 0){
	 	Y.one("#id_new_bundle_table").setStyle("display", "");
	 }
	 
	 var toggleDetailsOfBundle = function(e){
	 	var show_id = e.currentTarget.get("id");
	 	if(Y.one("#id_description_"+show_id).getStyle("display")=="none"){
	 		e.currentTarget.get("children").setStyle("display", null);
	 		e.currentTarget.setStyle("cursor", "n-resize");
	 		e.currentTarget.set("title", Y.one("input[name=hide_string]").get("value"));
	 	}else{
	 		Y.one("#id_description_"+show_id).setStyle("display", "none");
	 		Y.one("#id_contained_courses_"+show_id).setStyle("display", "none");
	 		e.currentTarget.setStyle("cursor", "s-resize");
	 		e.currentTarget.set("title", Y.one("input[name=enlarge_string]").get("value"));
	 	}
	 	
	 }
	 Y.all("ul.connectionlist li").on("click", toggleDetailsOfBundle);
	 
	 var toggleBundleManagement = function(){
	 	if(Y.one("#id_bundle_management").getStyle("display")=="none"){
	 		Y.one("#id_bundle_management").setStyle("display", null);
	 		Y.one("#id_toggle_bundle_management").setStyle("cursor", "n-resize");
	 		Y.one("#id_toggle_bundle_management").set("title", Y.one("input[name=hide_string]").get("value"));
	 	}else{
	 		Y.one("#id_bundle_management").setStyle("display", "none");
	 		Y.one("#id_toggle_bundle_management").setStyle("cursor", "s-resize");
	 		Y.one("#id_toggle_bundle_management").set("title", Y.one("input[name=enlarge_string]").get("value"));
	 	}
	 }
	 Y.on("click", toggleBundleManagement, "#id_toggle_bundle_management");
	 
	 var selectedBundle = function(){
	 	// new bundle
	 	if(Y.one("#id_addToBundle").get("value") == -1){
	 		Y.one("input[name=bundleId]").set("value", 0);
	 		Y.one("#id_name_of_bundle").set("value", "");
	 		Y.one("#id_description_of_bundle").set("innerHTML", "");
	 		Y.one("#id_new_bundle_table").setStyle("display", "");
	 	}
	 	
	 	// add course to existing bundle
	 	if(Y.one("#id_addToBundle").get("value") > 0){
	 		Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/store_bundle_connection.php");
    		var mform1 = Y.one("#mform1");
    		mform1.submit();
	 	} 
	 }
	 Y.on("change", selectedBundle, "#id_addToBundle");
	 
	 var addCourseToList = function(){
	 	Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/store_bundle_connection.php");
    	var mform1 = Y.one("#mform1");
    	mform1.submit();
	 }
	 Y.on("change", addCourseToList, "#id_addCourse");
	 
	 /**
	  * create a new bundle and store it in database
	  */
	 var submitBundleSubform = function(){
	 	//check if name of bundle is empty
	 	if(Y.one("#id_name_of_bundle").get("value") != ""){
	 		Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/store_bundle.php");
    		var mform1 = Y.one("#mform1");
    		mform1.submit();
	 	}else{
	 		alert("Missing name of bundle!");
	 	}
	 }
	 Y.on("click", submitBundleSubform, "#id_bundle_submit");
	 
	 /**
	  * remove course-to-bundle connection
	  */
	 var removeBundleConnection = function(e) {
    	Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/remove_bundle_connection.php");
    	Y.one("input[name=bcid]").set("value", e.currentTarget.get("name"));
    	var mform1 = Y.one("#mform1");
    	mform1.submit();
	}
	Y.all("ul.connectionlist a").on("click", removeBundleConnection);
	
	/**
	 * remove bundle from database
	 */
	 var removeBundle = function(e) {
	 	Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/remove_bundle.php");
    	Y.one("input[name=bundleId]").set("value", e.currentTarget.get("name"));
       	var mform1 = Y.one("#mform1");
    	mform1.submit();
	 }
	 Y.all("#id_bundle_management a").on("click", removeBundle);
	 
	 /**
	  *
	  * path configuration
	  *
	  */
	  
	 /**
	  * set bundle to configure paths for
	  */
	  var selectBundleForPath = function(){
	  	var splittedLocation = document.location.href.split("&lpbid");
	  	var newLocation = splittedLocation[0];
	  	if(splittedLocation.length > 1){
	  		var restLocation = splittedLocation[1].split("&");
	  		for(var i=1; i<restLocation.length; i++){
	  			newLocation += "&"+restLocation[i];
	  		}

	  	}
	  	document.location = newLocation+"&lpbid="+Y.one("#id_learning_path_bundle_select").get("value");
	  }
	  Y.on("change", selectBundleForPath, "#id_learning_path_bundle_select");
	  
	  /**
	   * enter new path name
	   */
	  var enterPathName = function(){
	  	// show text field to enter a new path name if needed
	  	if(Y.one("#id_learning_path_select").get("value")==-1){
	  		Y.one("#id_div_learning_path_select").setStyle("display", "none");
	  		Y.one("#id_new_path").setStyle("display", null);
	  		Y.one("input[name=pathId]").set("value", 0);
	  	}else{
	  	// reload page with selected path id
	  		var splittedLocation = document.location.href.split("&pathId");
	  		var newLocation = splittedLocation[0];
	  		if(splittedLocation.length > 1){
	  			var restLocation = splittedLocation[1].split("&");
	  			for(var i=1; i<restLocation.length; i++){
	  				newLocation += "&"+restLocation[i];
	  			}
	  		}
	  		document.location = newLocation+"&pathId="+Y.one("#id_learning_path_select").get("value");
	  	}
	  }
	  Y.on("change", enterPathName, "#id_learning_path_select");
	  
	  /**
	   * store path settings
	   */
	  var storePathSettings = function(){
	  	Y.one("#mform1").set("action", M.cfg['wwwroot']+"/blocks/semantic_web/store_path.php");
    	//Y.one("input[name=lpbid]").set("value", e.currentTarget.get("name"));
       	var mform1 = Y.one("#mform1");
       	if(Y.one("#id_learning_path_select").get("value")){
       		mform1.submit();
       	}else{
       		alert("Please select a learning path!");
       		Y.one("#id_select_add_pathnode").set("value", "0");
       	}
    			
	  }
	  Y.on("click", storePathSettings, "#id_button_new_path");
	  Y.on("change", storePathSettings, "#id_select_add_pathnode");
	  
	  /**
	   * remove node from path
	   */
	  var removeNodeFromPath = function(e){
	  	// Configuration object for POST transaction
		var cfg = {
			method: "POST",
			data: "ntd="+e.currentTarget.get("name")+"&pathId="+Y.one("input[name=pathId]").get("value")
		};
		
		var handleFailure = function (){
			alert("Failure: Node not removed!");
		}
		var handleSuccess = function(){
			document.location = document.location.href.substring(0, document.location.href.indexOf("#"));
		}
		
		Y.on('io:failure', handleFailure);
		Y.on('io:success', handleSuccess);
		
		var phpurl = M.cfg['wwwroot']+"/blocks/semantic_web/removePathNode.php";
		
		var request = Y.io(phpurl, cfg);
	  }
	  Y.all("#id_path_node_list a").on("click", removeNodeFromPath);
	  
	  /**
	   * remove learning path
	   */
	  var removeLearningPath = function(){
	  	var cfg = {
			method: "POST",
			data: "pathId="+Y.one("#id_learning_path_select").get("value")
		};
		
		var handleFailure = function (){
			alert("Failure: Learning path not removed!");
		}
		var handleSuccess = function(){
			document.location = document.location.href.substring(0, document.location.href.indexOf("&pathId"));
		}
		
		Y.on('io:failure', handleFailure);
		Y.on('io:success', handleSuccess);
		
		var phpurl = M.cfg['wwwroot']+"/blocks/semantic_web/removePath.php";
		
		var request = Y.io(phpurl, cfg);
	  }
	  Y.one("#id_delete_path").on("click", removeLearningPath);
 };

 //! actions triggered by elements of the semantic web block
 M.block_semantic_web.init_popup_actions = function(Y){
 	this.Y = Y;
 	
 	/**
 	 * set style attributes of miniweb iframe container
 	 */ 
 	Y.one("#id_miniWebContainer").setStyle("height", "180px");
 	Y.one("#id_miniWebContainer").setStyle("width", Y.one("#region-pre").getStyle("width"));
 	
 	
 	/**
 	 * set style attributes of miniweb iframe
 	 */ 
 	Y.one("#id_miniWeb").setStyle("position", "absolute");
  	Y.one("#id_miniWeb").setStyle("width", Y.one("#id_miniWebContainer").getStyle("width"));
 	Y.one("#id_miniWeb").setStyle("height", "180px");
 	Y.one("#id_miniWeb").setStyle("border-width", "0");
 	Y.one("#id_miniWeb").setStyle("text-align", "center");
 	Y.one("#id_miniWeb").setStyle("border", "0");
 	
 	
 	/**
 	 * add a transparent div layed over the miniweb iframe to get click event
 	 */
 	Y.one("#id_showWeb").setStyle("position", "absolute");
 	Y.one("#id_showWeb").setStyle("width", Y.one("#id_miniWebContainer").getStyle("width"));
 	Y.one("#id_showWeb").setStyle("height", Y.one("#id_miniWebContainer").getStyle("height"));
 	Y.one("#id_showWeb").setStyle("cursor", "hand");
 	Y.one("#id_showWeb").setStyle("cursor", "pointer");
 	 
 	
 	/**
 	 * disable and dimm the page
 	 */
 	var dimmPage = function() {
 		Y.one("body").append("<div id=\"id_dimmDiv\"></div>");
 		
 		Y.one("#id_dimmDiv").setStyle("position", "absolute");
 		Y.one("#id_dimmDiv").setStyle("top", "0px");
 		Y.one("#id_dimmDiv").setStyle("left", "0px");
 		Y.one("#id_dimmDiv").setStyle("background", "white");
 		Y.one("#id_dimmDiv").setStyle("opacity", "0.95");
 		Y.one("#id_dimmDiv").setStyle("width", "100%");
 		Y.one("#id_dimmDiv").setStyle("height", "100%");
 	}
 	
 	/**
 	 * add the sematic web iframe
 	 */
 	 var addWebFrame = function() {
 	 	Y.one("body").append("<iframe name=\"webframe\" id=\"id_webframe\" src=\""+M.cfg['wwwroot']+"/blocks/semantic_web/SemanticWeb/semanticweb.php\"></iframe>");
 	 	
 	 	Y.one("#id_webframe").setStyle("position", "absolute");
 	 	Y.one("#id_webframe").setStyle("width", "100%");
 	 	Y.one("#id_webframe").setStyle("height", "100%");
 	 	Y.one("#id_webframe").setStyle("top", "0px");
 	 	Y.one("#id_webframe").setStyle("left", "0px");
 	 	Y.one("#id_webframe").setStyle("border", "0px");
 	 }
 	 
 	 /**
 	 * add the edit metadata iframe
 	 */
 	 var addMetaDataFrame = function () {
 		 Y.one("body").append("<iframe id=\"id_metadataframe\" src=\""+Y.one("#id_linkEditMetaData").get("value")+"\"></iframe>");
 		 
 		 Y.one("#id_metadataframe").setStyle("position", "absolute");
 		 Y.one("#id_metadataframe").setStyle("width", 0.7*Y.one("#page").get("winWidth"));
 		 Y.one("#id_metadataframe").setStyle("height", Y.one("#page").get("winHeight"));
 		 Y.one("#id_metadataframe").setStyle("top", "0px");
 		 Y.one("#id_metadataframe").setStyle("left", 0.15*Y.one("#page").get("winWidth"));
 		 Y.one("#id_metadataframe").setStyle("border", "0px");
 		 parent.document.body.setAttribute("overflow", "hidden");
 	 }
 	 
 	
 	/**
 	 * show semantic navigation div
 	 */
 	var showWebDiv = function() {
 		dimmPage();
 		window.scrollTo(0,0);
 		addWebFrame();
 		if(Y.one("#id_dimmDiv")){
 			Y.one("#id_dimmDiv").on("click", hidePopup);
 		}
 	}
 	if(Y.one("#id_showWeb") != null) Y.one("#id_showWeb").on("click", showWebDiv);
 	
 	/**
 	 * show metadata div
 	 */
 	var showMetaDataDiv = function() {
 		dimmPage();
 		addMetaDataFrame();
 		Y.one("#id_dimmDiv").on("click", hidePopup);
 	}
 	if(Y.one("#id_editMetaData") != null) Y.one("#id_editMetaData").on("click", showMetaDataDiv);
 	
 	/**
 	 * hide popup (dimmDiv and metadata div or semantic navigation div)
 	 */
 	 var hidePopup = function(e) {
 	 	Y.one("#id_dimmDiv").remove(true);
 	 	if(Y.one("#id_webframe")){
 	 		Y.one("#id_webframe").remove(true);
 	 	}
 	 	if(Y.one("#id_metadataframe")){
 	 		Y.one("#id_metadataframe").remove(true);
 	 	}
 	 }
 	 
 	 /**
 	  * get sesion vars and manipulate ui
 	  */
 	  Y.io(M.cfg['wwwroot']+"/blocks/semantic_web/get_session_vars.php", {
 	  	on: {
 	    	success: function(id, o) {
 	      		var dasisSession = JSON.parse(o.responseText);
 	      		if(dasisSession["courseHasBundle"] == false && Y.one("#id_bundle_selection") != null) Y.one("#id_bundle_selection").setStyle("display", "none");
    	    	if(dasisSession["bundleHasPath"] == false && dasisSession["adaption"] == false && Y.one("#id_path_control") != null) Y.one("#id_path_control").setStyle("display", "none");
    	    	if(dasisSession["partOfWeb"] == false && window.location.href.search("/course/") == -1) Y.detach("click", Y.showWebDiv, "#id_showWeb");
 	      	}
 	   	}
 	  });
 	 
 	 /**
 	  * set variables of moodles session object
 	  */
 	  var setSessionVar = function(e){
 	  	// Configuration object for POST transaction
	  	var cfg = {
	  		method: "POST",
	  		data: "key="+e.currentTarget.get("name")+"&value="+e.currentTarget.get("value"),
	  		arguments: {}
	  	};
	  	
	  	var handleComplete = function() {
	  		if(e.currentTarget.get("name")=="userDepth"){
	  			Y.one("#id_miniWeb").set("src", Y.one("#id_miniWeb").get("src"));
	  		} else {
	  			document.location = document.location;
	  		}
	    }
	   	Y.on('io:complete', handleComplete);
	  	
	  	var phpurl = M.cfg['wwwroot']+"/blocks/semantic_web/set_sessionVar.php";
	  	var request = Y.io(phpurl, cfg);
 	  }
 	  if(Y.one("#id_bundle_selection") != null) Y.on("change", setSessionVar, "#id_bundle_selection"); // set selected bundle to navigate in
 	  if(Y.one("#id_path_select") != null) Y.on("change", setSessionVar, "#id_path_select"); // set selected path
 	  Y.on("change", setSessionVar, "#id_select_depth");
 	  
 	  /**
 	   * walk along the learning path by clicking the buttons
 	   */
 	   var goToPrevNode = function() {
 	   		document.location = Y.one("#id_button_lastNode").get("value");
 	   }
 	   if(Y.one("#id_button_lastNode") != null) Y.on("click", goToPrevNode, "#id_button_lastNode");
 	   
 	   var goToNextNode = function() {
 	   		document.location = Y.one("#id_button_nextNode").get("value");
 	   }
 	   if(Y.one("#id_button_nextNode") != null) Y.on("click", goToNextNode, "#id_button_nextNode");
  };
 
 M.block_semantic_web.init_web_actions = function(Y){
 	this.Y = Y;
 	
 	var dd = new Y.DD.Drag({
 		node:"#caption"
 	});
 };
/*****************************************************************************************
 * GLOBALS
 ****************************************************************************************/
var SIDEBAR_TIMER;
var SIDEBAR_INTERVAL = 60000; // 1000 = 1 second

var BOXSCORE_TIMER;
var BOXSCORE_INTERVAL = 5000; // 1000 = 1 second



/*****************************************************************************************
 * initAJAX(...)
 * Purpose: Sets up the system with AJAX defaults
 ****************************************************************************************/
function initAJAX() {
    
    $.ajaxSetup({
        url:        "ajax/server.php",
        type:       "post",

        timeout:    30000,
        cache:      "false",
        dataType:   "json",
    });

}

/*****************************************************************************************
 * logError(...)
 * Purpose: For debugging purposes, this function adds a log comment to DOM
 ****************************************************************************************/
function logError() {
    
    
}





/*****************************************************************************************
 * updateBoxScoreUI(...)
 * Purpose: Retrieves new JSON data about box scores
 ****************************************************************************************/
function updateBoxScoreUI(data) {

}

/*****************************************************************************************
 * errorBoxScoreUI(...)
 * Purpose: Handles an error with Box Score update
 ****************************************************************************************/
function errorBoxScoreUI(xhr,status,error) {
	alert(status);
}

/*****************************************************************************************
 * runBoxScoreAJAX(...)
 * Purpose: Retrieves new JSON data about box score
 ****************************************************************************************/
function runBoxScoreAJAX() {

    $.ajax({
        data:       {rqst:"boxscore", game:$("#hGameID").val()},
        success:    function(data) { updateBoxScoreUI(data); },
        error:      function(xhr,status,error) { errorBoxScoreUI(xhr,status,error); }
    });

	/* Start Timer */
    BOXSCORE_TIMER = setTimeout("runBoxScoreAJAX()",BOXSCORE_INTERVAL);

}

/*****************************************************************************************
 * initBoxScoreUI(...)
 * Purpose: Initializes the UI events for this element
 ****************************************************************************************/
function initBoxScoreUI(gameID) {

	/* Set gameID */
	$("#hGameID").val(gameID);
	
	/* Start Timer */
	if(BOXSCORE_TIMER == null)
	    BOXSCORE_TIMER = setTimeout("runBoxScoreAJAX()",BOXSCORE_INTERVAL);

}





/*****************************************************************************************
 * updateSidebarUI(...)
 * Purpose: Retrieves new data and updates interface
 ****************************************************************************************/
function updateSidebarUI(data) {

    for (game in data) {
        $(".sidebar #"+game+" tr.away_team td.team_score").empty().
        	append(data[game].away_runs);
        $(".sidebar #"+game+" tr.home_team td.team_score").empty().
        	append(data[game].home_runs);
        $(".sidebar #"+game+" .status td").empty().append(data[game].status);
    }
    
}

/*****************************************************************************************
 * errorSidebarUI(...)
 * Purpose: Handles an error with Sidebar AJAX update
 ****************************************************************************************/
function errorSidebarUI(xhr,status,error) {

    alert(status);
    
}

/*****************************************************************************************
 * runSidebarAJAX(...)
 * Purpose: Retrieves new JSON data about sidebar scores
 ****************************************************************************************/
function runSidebarAJAX() {
    
    $.ajax({
        data:       {rqst:"sidebar"},
        success:    function(data) { updateSidebarUI(data); },
        error:      function(xhr,status,error) { errorSidebarUI(xhr,status,error); }
    });
    
    /* Start Timer */
    SIDEBAR_TIMER = setTimeout("runSidebarAJAX()",SIDEBAR_INTERVAL);
    
}

/*****************************************************************************************
 * initSidebarUI(...)
 * Purpose: Initializes the UI events for this element
 ****************************************************************************************/
function initSidebarUI() {
    
    /* Add mouse over and out events */
    $(".sidebar .miniboxscore table").mouseover(
        function() { $(this).addClass("hovering"); }
    ).mouseout(
        function() { $(this).removeClass("hovering"); }
    ).click(
    	function() { initBoxScoreUI(this.id); }
    );
    
    /* Start Timer */
    SIDEBAR_TIMER = setTimeout("runSidebarAJAX()",SIDEBAR_INTERVAL);
      
}
/*****************************************************************************************
 * GLOBALS
 ****************************************************************************************/
var SIDEBAR_TIMER;
var SIDEBAR_INTERVAL = 60000; // 1000 = 1 second





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
 * updateSidebarUI(...)
 * Purpose: Retrieves new data and updates interface
 ****************************************************************************************/
function updateSidebarUI(data) {

    for (game in data) {
        $(".sidebar #"+game+" tr.away_team td.team_score").empty().append(data[game].away_runs);
        $(".sidebar #"+game+" tr.home_team td.team_score").empty().append(data[game].home_runs);
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
        function() {
            $(this).addClass("hovering");
        }
    ).mouseout(
        function() {
            $(this).removeClass("hovering");
        }
    );
    
    /* Start Timer */
    SIDEBAR_TIMER = setTimeout("runSidebarAJAX()",SIDEBAR_INTERVAL);
      
}
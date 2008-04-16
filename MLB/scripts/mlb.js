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
    
}
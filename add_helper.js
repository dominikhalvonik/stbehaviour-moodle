window.onload = init;

/**
 * Method responsible for changing the activities for each course
 */
function init() {
     $('#id_coursename').change(function() {
        $('#id_activityname').load('getter.php?coursenameid=' + $('#id_coursename').val());
    });
	
	 $('#id_coursename_act').change(function() {
        $('#id_activity_act').load('getter.php?coursid=' + $('#id_coursename_act').val());
    });
}
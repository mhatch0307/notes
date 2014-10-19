$(document).ready(function(){
	loadClasses();
});

function loadClasses()
{
	$.post("/resources/delegates/classDelegate.php",{action:"loadClasses"}, function(result){
		$("#classes").html(result);
	});
}

function loadTopics()
{
	var classID = $("#classSelector").val();
	var action = "loadTopics";
	$.post("/resources/delegates/topicDelegate.php", {action:action, classID:classID}, function(result){
		$("#topics").html(result);
		$(".accordion").accordion({
			heightStyle: "content",
			collapsible: true,
			active: false
		});
		$(".loadTopicNotes").click(function(){
			loadNotes($(this).attr("topicID"));
		});
	});
}

function loadNotes(topicID)
{
	var action = "loadNotes";
	var classID = $("#classSelector").val();
	$.post("/resources/delegates/noteDelegate.php", {action:action, topicID:topicID, classID:classID}, 
		function(result){
		$("#noteView").html(result);
	});
}

$(document).ready(function(){
	loadClasses();
	$("#editClasses").dialog({
		autoOpen: false,
		width: 320,
		position: {my: "left+250 top+10", at: "left top", of: window},
	});
	
	$("#editTopic").dialog({
		autoOpen: false,
		width: 320,
		position: {my: "left+250 top+10", at: "left top", of: window}
	});
	
	window.onkeyup = function(e)
	{
		var key = e.keyCode ? e.keyCode : e.which;
		if (key == 13)
		{
			$currNote = $(':focus').parent();
			topicID = $currNote.attr("topicID");
			noteOrder = $currNote.attr("noteOrder");
			indentCount = $currNote.attr("indentCount");
			parentID = $currNote.attr("parentID");
			noteID = $currNote.attr("noteID");
			classID = $("#classSelector").val();
			if(noteID != undefined && noteID != -1)
			{
				$currNote.parent().append(
					'<span class = "noteEntry" noteID = -1 topicID = '+topicID+' noteOrder = '+noteOrder+' indentCount = '+indentCount+' parentID = '+parentID+'>'	
					+	'<input id = "newNote" class = "keyTerm" name = "keyTerm" noteID = -1 value = ""/>'
					+	'<input class = "description" name = "description" noteID = -1 value = ""/>'
					+'</span>'
				);
				$("#newNote").blur(function(){
					keyTerm = $(this).val();
					$.post("/resources/delegates/noteDelegate.php", {action:"insertNote", keyTerm:keyTerm, noteOrder:noteOrder, parentID:parentID, topicID:topicID, classID:classID}, function(result){
						loadNotes(topicID);
					});
				});
				$currNote.parent().find("[noteID = -1]").find("[name = 'keyTerm']").focus();
			}
			else
			{
				$currNote.remove();
			}
		}
	}
	
});

function loadClasses()
{
	$.post("/resources/delegates/classDelegate.php",{action:"loadClasses"}, function(result){
		$("#classes").html(result);
		$("#noteView").html("");
	});
	
	$.post("/resources/delegates/classDelegate.php",{action:"loadEditClasses"}, function(result){
		$("#editClasses").html(result);
		
		$(".updateClass").click(function(){
			var classID = $(this).parent().parent().attr("classID");
			var name =  $(this).parent().parent().find("input[name = class]").val();
			$.post("/resources/delegates/classDelegate.php", {action:"editClass", classID:classID, name:name}, function(result){
				loadClasses();
				$("#editClasses").dialog("open");
			});
		});
		
		$(".hideClass").click(function(){
			var classID = $(this).parent().parent().attr("classID");
			$.post("/resources/delegates/classDelegate.php", {action:"hideClass", classID:classID}, function(result){
				loadClasses();
				$("#editClasses").dialog("open");
			});
		});
		
		$(".addClass").click(function(){
			var name = $(this).parent().parent().find("input[name = class]").val();
			if(name != "" || name != null)
			{	
				$.post("/resources/delegates/classDelegate.php", {action:"addClass", name:name}, function(result){
					loadClasses();
					$("#editClasses").dialog("open");
				});
			}
		});
		
	});
}

function editClasses()
{
	$("#editClasses").dialog("open");
	$("#topics").html("");
}

function editTopic(topicID)
{
	$.post("/resources/delegates/topicDelegate.php", {action:"editTopic", topicID:topicID}, function(result){
		$("#editTopic").html(result);
		$("#editTopic").dialog("open");
		
		$(".updateTopic").click(function(){
			var topicID = $(this).parent().parent().attr("topicID");
			var name =  $(this).parent().parent().find("input[name = topic]").val();
			$.post("/resources/delegates/topicDelegate.php", {action:"updateTopic", topicID:topicID, name:name}, function(result){
				loadTopics();
				$("#editTopic").dialog("close");
			});
		})
		
		$(".hideTopic").click(function(){
			var topicID = $(this).parent().parent().attr("topicID");
			$.post("/resources/delegates/topicDelegate.php", {action:"hideTopic", topicID:topicID}, function(result){
				loadTopics();
				$("#editTopic").dialog("close");
			});
		});
	});
}

function loadTopics()
{
	$("#noteView").html("");
	var classID = $("#classSelector").val();
	if(classID == -1)
	{
		editClasses();
	}
	else
	{
		$("#editClasses").dialog("close");
		var action = "loadTopics";
		$.post("/resources/delegates/topicDelegate.php", {action:action, classID:classID}, function(result){
			$("#topics").html(result);
			$(".accordion").accordion({
				heightStyle: "content",
				collapsible: true,
				active: false
			});
			
			$(".loadTopicNotes").click(function(){
				loadNotes($(this).parent().attr("topicID"));
			});
			
			$(".editTopic").click(function(){
				editTopic($(this).parent().attr("topicID"));
			});
			
			$(".addTopic").click(function(){
				var parentID = $(this).attr("parentID");
				var classID = $(this).attr("classID");
				var name = window.prompt("Enter the topic name: ");
				if(name != "" && name != null)
				{
					$.post("/resources/delegates/topicDelegate.php", {action:"addTopic",classID:classID, parentID:parentID, name:name}, function(result){
						loadTopics();
					});
				}
			});
			
		});
	}
}

function addTopic()
{
	var classID = $("#classSelector").val();
	var name = window.prompt("Enter the topic name: ");
	if(name != "" && name != null)
	{
		$.post("/resources/delegates/topicDelegate.php", {action:"addTopic", classID:classID, parentID:0, name:name}, function(result){
			loadTopics();
		});
	}
}

function loadNotes(topicID)
{
	var action = "loadNotes";
	var classID = $("#classSelector").val();
	$.post("/resources/delegates/noteDelegate.php", {action:action, topicID:topicID, classID:classID}, 
		function(result){
		$("#noteView").html(result);
		
		$(".keyTerm").blur(function(){
			var noteID = $(this).attr("noteID");
			var keyTerm = $(this).val();
			$.post("/resources/delegates/noteDelegate.php", {action:"updateKeyTerm", noteID:noteID, keyTerm:keyTerm}, function(result){
				
			});
		});
		
		$(".description").blur(function(){
			var noteID = $(this).attr("noteID");
			var description = $(this).val();
			$.post("/resources/delegates/noteDelegate.php", {action:"updateDescription", noteID:noteID, description:description}, function(result){
				
			});
		});
		
	});
}

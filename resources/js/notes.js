var keys = {};

$(document).keydown(function (e){
	keys[e.which] = true;
	if (keys[13] == true) // insert new note
	{
		keys = {};
		var currNote = $(':focus').parent();
		var topicID = currNote.attr("topicID");
		var noteOrder = currNote.attr("noteOrder");
		var indentCount = currNote.attr("indentCount");
		var parentID = currNote.attr("parentID");
		var noteID = currNote.attr("noteID");
		var classID = $("#classSelector").val();
		var currNoteOrder = parseInt(currNote.attr("noteOrder")) + 1;
		if(noteID != undefined && noteID != -1)
		{
			$.post("/resources/delegates/noteDelegate.php", {action:"insertNote", keyTerm:"", noteOrder:noteOrder, parentID:parentID, topicID:topicID, classID:classID}, function(result){
				loadNotes(topicID, currNoteOrder);
			});
		}
	}
	else if(keys[17] && keys[39]) // indent the current note
	{
		keys = {};
		var focused = $(':focus');
		if(focused.attr("noteID") > 0)
		{
			var currNote = focused.parent();
			var topicID = currNote.attr("topicID");
			var prevNote = currNote.parent().prev();
			if(prevNote.prop('tagName') == "OL"){
				prevNote = prevNote.prev();
			}
			prevNote = prevNote.children();
			var prevNoteID = prevNote.attr("noteID");
			var currNoteID = currNote.attr("noteID");
			var currNoteOrder = parseInt(currNote.attr("noteOrder"));
			if(prevNoteID > 0)
			{
				$.post("/resources/delegates/noteDelegate.php", {action:"updateParent", noteID:currNoteID, parentID:prevNoteID}, function(result){
					loadNotes(topicID, currNoteOrder);
				});
			}
		}
		
	}
	else if(keys[17] && keys[37]) // unindent the current note
	{
		keys = {};
		var focused = $(':focus');
		if(focused.attr("noteID") > 0)
		{
			var currNote = focused.parent();
			var topicID = currNote.attr("topicID");
			var parentNote = currNote.parent().parent().prev().children();
			if(parentNote.attr("parentID") == parentNote.attr("noteID"))
			{
				parentID = currNote.attr("noteID");
			}
			else
			{
				parentID = parentNote.attr("parentID");
			}
			var currNoteID = currNote.attr("noteID");
			var currNoteOrder = parseInt(currNote.attr("noteOrder"));
			if(parentID > 0)
			{
				$.post("/resources/delegates/noteDelegate.php", {action:"updateParent", noteID:currNoteID, parentID:parentID}, function(result){
					loadNotes(topicID, currNoteOrder);
				});
			}
			
		}
		
	}
	
});

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
	
	$("#signOut").click(function(){
		$.post("/resources/utilities/logout.php", {}, function(result){
			
		});
	});
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
		
		$(".modifyClass").click(function(){
			var name = $(this).parent().parent().find("input[name = class]").val();
			var classID = $(this).parent().parent().attr("classID");
			if(name != "" || name != null)
			{
				$.post("/resources/delegates/classDelegate.php", {action:"modfiyClass", name:name, classID:classID}, function(result){
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

function loadNotes(topicID, noteFocus)
{
	var action = "loadNotes";
	var classID = $("#classSelector").val();
	$.post("/resources/delegates/noteDelegate.php", {action:action, topicID:topicID, classID:classID}, 
		function(result){
		if(result == 0)
		{
			$.post("/resources/delegates/noteDelegate.php", {action:"insertNote", keyTerm:"", noteOrder:1, parentID:-1, topicID:topicID, classID:classID}, function(result){
				loadNotes(topicID, 1);
			});
		}
		else
		{
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
			if(noteFocus > 0)
			{
				$("[noteOrder = "+noteFocus+"]").children(".keyTerm").focus();
			}
		}
	});
}


$(document).ready(function() {
    $('#feedback').load('welcome_helper.php').show();

    $('#recipient_input').keyup(function() {
        var recipient_input = $("#recipient_input").val();
        $.post('welcome_helper.php', { recipient: recipient_input, type: "recipientCheck" },
        function(data){
            $('#feedback').html(data).show();

            // Responsible for disabling/enabling chat button
            if(!$('#feedback:contains("Recipient Exists")').length > 0){
                document.getElementById("chat_button").disabled = true;
            }
            else{
                document.getElementById("chat_button").disabled = false;
            }
        });
    });
});

/* jQuery for taking the recipients name and adding it to Group and User_Group tables
- also used to pass an array in JSON containing group id, user id, recipient id in that order as a hidden field to the message form */
$(document).ready(function() {
    $('#chat_button').click(function() {
        var recipient = $("#recipient_input").val();
        $.post('welcome_helper.php', { data : recipient, type: "groupChat" }, 
        function(data){
            var id_array = data;
            $("#hidden_array").val(id_array);

            // Responsible for resetting new message container
            if($('#feedback:contains("Recipient Exists")').length > 0){
                document.getElementById("recipient_input").value = "";
                document.getElementById("new_message_container").style.display = "none";
                document.getElementById("feedback").innerHTML = "";
                document.getElementById("messagebar_container").style.display = "block";
            }
        });
    });
});

// jQuery for replacing submission form when sending a message
/*
$(document).ready(function() {
    $('#send_button').click(function() {
        var message = $("#message_input").val();
        var id_array = $("#hidden_array").val();
        $.post('welcome_helper.php', {message: message, id_array: id_array, type: "sendMessage"},
        function(data){
            var messages = "";
            var objId_array = JSON.parse(id_array);
            var user_id = objId_array[1];
            var recipient_id = objId_array[2];
            
            messages += "<p class='user_messages'>" + JSON.parse(data) + "</p><br>";
            
            $("#message_area").html(messages).show();
            document.getElementById("message_input").value = "";
        });
    });
});
*/
/* Used to retrieve data of user's existing chats (recipients name, recip_id, group_id) */
$(document).ready(function() {

    var messages = "";

    $.post('welcome_helper.php', {login_username: login_username, type: "chatThumbs"},
    function(data){ // data holds JSON representation as string
        var obj = JSON.parse(data); // converts string to JSON object
        var i, usernames = "";
        
        for (i = 0; i < obj.length ; i++){
            usernames += "<button id='thumbnail" + obj[i].group_id + "' class='thumbnail' data-value='" + obj[i].user_id + "' value='" + obj[i].group_id + "'>" + obj[i].username + "</button><br>"; // we concatenate all usernames within JSON object
        }
        $("#message_list").html(usernames).show();

        $('.thumbnail').click(function() {

            document.getElementById("messagebar_container").style.display = "block";

            var recip_id = $(this).attr("data-value");
            var group_id = $(this).attr("value");
            $.post('welcome_helper.php', {recip_id: recip_id, group_id: group_id, type: "loadChat"}, 
            function(data) {
                var obj = JSON.parse(data);
                
                $("#hidden_array").val(obj["id_array"]);
                
                var i;
                var id_array = JSON.parse(obj["id_array"]);
                var user_id = id_array[1];
                var recipient_id = id_array[2];
                for(i = 0; i < obj["chat_messages"].length; i++){
                    // so here we have to separate what the users and recipients messages are so they can
                    // be divided on the main message area
                    // if (user_id is == to obj["chat_messages"][i].creator_id)
                    if (user_id == obj["chat_messages"][i].creator_id){
                        // messages += "..." -> positioning has to be on right side
                        messages += "<p class='user_messages'>" + obj["chat_messages"][i].message_body + "</p><br>";
                    }
                    // else if (recipient_id is == to obj["chat_messages"][i].creator_id)
                    else if (recipient_id == obj["chat_messages"][i].creator_id){
                        // messages += "..." -> positioning has to be on the left side
                        messages += "<p class='recip_messages'>" + obj["chat_messages"][i].message_body + "</p><br>";
                    }
                    //messages += "<p class='messages'>" + obj["chat_messages"][i].message_body + "</p><br>";
                    
                }
                
                $("#message_area").html(messages).show();
                
            });
        });
       
    });

    $('#send_button').click(function() {
        var message = $("#message_input").val();
        var id_array = $("#hidden_array").val();
        $.post('welcome_helper.php', {message: message, id_array: id_array, type: "sendMessage"},
        function(data){
            
            var objId_array = JSON.parse(id_array);
            var user_id = objId_array[1];
            var recipient_id = objId_array[2];
            
            messages += "<p class='user_messages'>" + JSON.parse(data) + "</p><br>";
            
            $("#message_area").html(messages).show();
            document.getElementById("message_input").value = "";
        });
    });


});

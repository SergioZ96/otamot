
$(document).ready(function() {
    $('#feedback').load('welcome_helper.php').show();

    $('#recipient_input').keyup(function() {
        var recipient_input = $("#recipient_input").val();
        $.post('welcome_helper.php', { recipient: recipient_input, type: "recipientCheck" },
        function(data,status){
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
        function(data,status){
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

$(document).ready(function() {
    $('#send_button').click(function() {
        var message = $("#message_input").val();
        var id_array = $("#hidden_array").val();
        $.post('welcome_helper.php', {message: message, id_array: id_array, type: "sendMessage"},
        function(data){
            $("#user_message_area").html(data).show();
            document.getElementById("message_input").value = "";
        });
    });
});

/* Used to retrieve data of user's existing chats (recipients name, recip_id, group_id) */
$(document).ready(function() {
    $.post('welcome_helper.php', {login_username: login_username, type: "chatThumbs"},
    function(data){ // data holds JSON representation as string
        var obj = JSON.parse(data); // converts string to JSON object
        var i, usernames = "";
        
        for (i = 0; i < obj.length ; i++){
            usernames += "<div class='thumbnail'>" + obj[i].username + "</div><br>"; // we concatenate all usernames within JSON object
        }
        $("#message_list").html(usernames).show();
    });
});

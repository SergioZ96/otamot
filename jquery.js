

$(document).ready(function() {

        function updateScroll() {
            var scrollDiv = document.getElementById("main");
            scrollDiv.scrollTop = scrollDiv.scrollHeight;
        }

        /*
         *  Enables ability to search through existing chats for desired recipients username
         */
        $('#search_box').keyup(function(){
            var chatSearch = $('#search_box').val();
            var chats = document.querySelectorAll('.thumbnail');
            for(var i = 0; i < chats.length; i++){
                var id = "#".concat(chats[i].id);
                var thumb_class = ".".concat(chats[i].className);
                // see if the innerhtml of any of the buttons w/ class 'thumbnail' match the search box input
                // if theres a match, show the button using its respective id
                if(chats[i].innerHTML.includes(chatSearch)){
                    $(id).show("slow", function(){
                        $(id).click(function(){
                            $(thumb_class).show();
                        })
                    });
                }
                // if field is empty, show all the thumbnails
                else if ($("#search_box").val() == "") {
                    $(thumb_class).show();
                }
                // if there are no matches, hide all of the thumbnails
                else {
                    $(id).hide();
                }
            }
        
        });

        /*
         *  Enables chat button if recipient exists, otherwise unable to create new message
         */
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










        /* 
         *  Acquires recipients name after chat button is clicked, adds it to Group and User_Group tables
         *  Also used to pass an array in JSON containing group id, user id, recipient id 
         *  in that order as a hidden field to the message form
         */  
        //var messages = "";
        $('#chat_button').click(function() {

            var recipient = $("#recipient_input").val();
            $.post('welcome_helper.php', { data : recipient, type: "groupChat" }, 
            function(data){
                var id_array = data;
                //$("#hidden_array").val(id_array);
                
           
                // Responsible for resetting new message container
                if($('#feedback:contains("Recipient Exists")').length > 0){
                    document.getElementById("recipient_input").value = "";
                    document.querySelector(".bg-modal").style.display = "none";
                    document.getElementById("feedback").innerHTML = "";
                    document.getElementById("messagebar_container").style.display = "block";
                }

                // Here we have to load the chat if the theres an existing chat
                
                var id_array = JSON.parse(id_array);
                var recip_id = id_array[2];
                var group_id = id_array[0];
                
                var chatRecip_selector = "#thumbnail" + recip_id;
                // if the chat thumbnail exists, proceed loading the messages for that chat
                if($(chatRecip_selector).length){

                    // responsible for removing dynamically created HTML messages when another thumbnail is clicked
                    // pretty much clearing the message area
                    $(".user_messages").remove();
                    $(".recip_messages").remove();
                    $("#message_area > br").remove();

                    updateMessages(recip_id, group_id);
                   
                }
            });
        });













        /*
         *  Loads chat thumbnails in the sidebar by using recipients username, user id, and group id
         *  Proceeds with creating HTML strings for each thumbnail
         */

        //var messages = "";

        $.post('welcome_helper.php', {login_username: login_username, type: "chatThumbs"},
        function(data){ 
            // data holds JSON representation as string
            // ex. "[{"username":"charliegeorge","user_id":3,"group_id":1},...]"
            var obj = JSON.parse(data); // converts string to JSON object
            // ex. 0 : {username: "charliegeorge", user_id: 3, group_id: 1}
            var i, usernames = "";
            
            for (i = 0; i < obj.length ; i++){
                usernames += "<button id='thumbnail" + obj[i].group_id + "' class='thumbnail' data-value='" + obj[i].user_id + "' value='" + obj[i].group_id + "'>" + obj[i].username + "</button>"; // we concatenate all usernames within JSON object
            }
            $("#message_list").html(usernames).show();

            /*
             *  Loads chat messages specific to recipient/chat after clicking recipient's thumbnail
             *  Proceeds with creating HTML strings for each message pertaining to the chat
             */
        
            $('.thumbnail').click(function() {
                
                // responsible for removing dynamically created HTML messages when another thumbnail is clicked
                // pretty much clearing the message area
                $(".user_messages").remove();
                $(".recip_messages").remove();
                $("#message_area > br").remove();


                document.getElementById("messagebar_container").style.display = "block";

                var recip_id = $(this).attr("data-value");
                var group_id = $(this).attr("value");

                updateMessages(recip_id, group_id);
                
            });
        
        });

        /*
         *  Responsible for displaying recently sent message in the main message area
         *  by adding concatenating HTML string to messages variable
         */

        
        $('#send_button').click(function() {
            var id_array = $("#hidden_array").val();
            var message = $("#message_input").val();
            if (message.localeCompare("") == 0){
                alert("Cannot send an empty message");
            }
            else{

                $.post('welcome_helper.php', {message: message, id_array: id_array, type: "sendMessage"},
                function(data){
                    
                    //M.messages += "<p class='user_messages'>" + JSON.parse(data) + "</p><br>";
                    $("#message_area").append("<p class='user_messages'>" + JSON.parse(data) + "</p><br>");
                    document.getElementById("message_input").value = "";
                    //$("#message_area").html(M.messages).show();
                    
                    updateScroll();
                    
                    
                });
            }
        });

        
        
        
        function updateMessages(recip_id, group_id) {
            //setInterval(function(){
            //format: group id, user id, recipient id from hidden array
            //var hidden_array = $("#hidden_array").attr("value");
            //var recip_id = hidden_array[2];
            //var group_id = hidden_array[0];

            $.post('welcome_helper.php', {recip_id: recip_id, group_id: group_id, type: "loadChat"}, 
            function(data) {

                //$(".user_messages").remove();
                //$(".recip_messages").remove();
                
                // cleans the messages string of past HTML messages
                //messages = "";
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
                        //messages += "<p class='user_messages'>" + obj["chat_messages"][i].message_body + "</p><br>";
                        $("#message_area").append("<p class='user_messages'>" + obj["chat_messages"][i].message_body + "</p><br>");
                    }
                    // else if (recipient_id is == to obj["chat_messages"][i].creator_id)
                    else if (recipient_id == obj["chat_messages"][i].creator_id){
                        // messages += "..." -> positioning has to be on the left side
                        //messages += "<p class='recip_messages'>" + obj["chat_messages"][i].message_body + "</p><br>";
                        $("#message_area").append("<p class='recip_messages'>" + obj["chat_messages"][i].message_body + "</p><br>");
                    }
                    //messages += "<p class='messages'>" + obj["chat_messages"][i].message_body + "</p><br>";
                    
                }
                //$("#message_area").html(messages).show();
                updateScroll();

            });
            //}, 5000);
            //return messages;
        } 

       

});


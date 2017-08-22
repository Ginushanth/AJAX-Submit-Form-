   $(document).ready(function(){


    //FORM SUBMISSION
    $('#submit-button').click('submit',function(e){
         e.preventDefault();    // This prevents form from being sumbitted
         //Get the values of the form inputs
         var name=$('#nameInput').val();
         var email=$('#emailInput').val();
         var company=$('#companyInput').val();
         var comments=$('#commentsInput').val();
         var submit=$('#submit-button').val();
         var dataString='name='+ name + '&email=' + email + '&company=' + company + '&comments='+comments+'&message-submit='+submit;

         //SEND form data to the PHP file to process
         $.ajax({
                type: "POST",
                url: "/practice/php/form.php",
                data: dataString,
                success: function (serverResponse) 
                {
                    //GET the PHP file response
                    var response=serverResponse;
                    var inputCheck=true;
                    //If there are any errors in the input file, the PHP file will echo it back

                    //Search for any error messages
                    if (response.search('Enter your name')!=-1)
                    {
                        $('#nameErr').text('*Enter your name');
                        inputCheck=false;
                    }
                    else if (response.search('Letters only')!=-1)
                    {
                        $('#nameErr').text('*Letters only');
                        inputCheck=false;
                    }
                    else
                    {
                        $('#nameErr').text('');
                    }
                    if (response.search('Not a valid email')!=-1)
                    {
                        $('#emailErr').text('*Not a valid email');
                        inputCheck=false;
                    }
                    else
                    {
                        $('#emailErr').text('');
                    }
                    if (response.search('Cannot be empty')!=-1)
                    {
                        $('#commentsErr').text('*Cannot be empty');
                        inputCheck=false;
                    }
                    else
                    {
                        $('#commentsErr').text('');
                    }
                    //If all the inputs are correct and filled in then display it has been sent and empty the fields
                    if (inputCheck==true)
                    {
                        $('#submitted').text('Your message has been sent!');
                        setTimeout(function(){
                            $('#submitted').text("");
                        },4000);
                        $('#nameInput').val("");
                        $('#companyInput').val("");
                        $('#commentsInput').val("");
                        $('#emailInput').val("");
                        document.getElementById("characters_remaining").innerHTML=1000;
                    }
                }
            }); // AJAX Get Jquery statment
    });
});
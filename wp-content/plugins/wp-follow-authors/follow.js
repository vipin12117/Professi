$jq =jQuery.noConflict();


/*
 * Validate Email Address
 */
var emailValidation = function(email) {

    $jq("#frm_msg").html("");
    $jq("#frm_msg").removeAttr("class");

    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    

    if(email == '' || (!emailReg.test(email))){
        $jq("#frm_msg").html("Please enter valid email");
        $jq("#frm_msg").addClass("error");
        return false;
    }else{
        return true;
    }

};


$jq(document).ready(function(){


    /*
     * Make initial subscription to service
     */
    $jq("#subscribeAuthors").live("click",function(){

        var email = $jq("#user_email").val();
        if(emailValidation(email)){
  
            jQuery.ajax({
                type: "post",
                url: ajaxData.ajaxUrl,
                data: "action=subscribe_to_wp_authors&nonce="+ajaxData.ajaxNonce+"&url="+ajaxData.currentURL+"&email="+email,
                success: function(message){
                    var result = eval('(' + message + ')');
                    if(result.error){
                        $jq("#frm_msg").html(result.error);
                        $jq("#frm_msg").addClass("error");
                    }
                    if(result.success){
                        $jq("#frm_msg").html(result.success);
                        $jq("#frm_msg").addClass("success");
                    }
         
                }
            });
        }

    });
    
    
    
    /*
     * Change Styles on Follow Actions
     */
    $jq(".follow").mouseenter(function(){
        $jq(this).css("background","#EEE").css("color","#5D9CDE");
        
    });
    $jq(".follow").mouseout(function(){
        $jq(this).css("background","#5092D7").css("color","#FFF");;
        
    });
    
    $jq(".following").live("mouseenter",function(){
        $jq(this).val("Unfollow");
        
    });
    $jq(".following").live("mouseout",function(){
        $jq(this).val("Following");        
    });
    
    
    /*
     * Follow a single author
     */
    $jq(".follow").live("click",function(){
        
        var activeObject = $jq(this);

        var email = $jq("#user_email").val();
        if(emailValidation(email)){
  
            jQuery.ajax({
                type: "post",
                url: ajaxData.ajaxUrl,
                data: "action=follow_wp_authors&author_id="+$jq(this).attr("data-author")+"&nonce="+ajaxData.ajaxNonce+"&url="+ajaxData.currentURL+"&email="+email,
                success: function(message){
                    var result = eval('(' + message + ')');
                    if(result.status == 'success' ){             
                        activeObject.val("Following");
                        activeObject.removeClass("follow").addClass("following");
                    }
                }
            });
        }
        
    });
    
    

    
    /*
    * Unfollow single author
    */
    $jq(".following").live("click",function(){


        var activeObject = $jq(this);

        var email = $jq("#user_email").val();
        if(emailValidation(email)){

            jQuery.ajax({
                type: "post",
                url: ajaxData.ajaxUrl,
                data: "action=unfollow_wp_authors&author_id="+$jq(this).attr("data-author")+"&nonce="+ajaxData.ajaxNonce+"&email="+email,
                success: function(message){
                    var result = eval('(' + message + ')');
                    if(result.status == 'success' ){

                        activeObject.val("Follow");
                        activeObject.removeClass("following").addClass("follow");
                    }

                }
            });
        }

    });
    
    
    /*
     * Load subscribed author list for given email
     */
    $jq("#loadFollowers").live("click",function(){
        var email = $jq("#user_email").val();
        if(emailValidation(email)){
  
            jQuery.ajax({
                type: "post",
                url: ajaxData.ajaxUrl,
                data: "action=load_subscribed_authors&nonce="+ajaxData.ajaxNonce+"&email="+email,
                success: function(message){
                    var result = eval('(' + message + ')');
                    $jq(".follow").each(function(){
                        var actObj = $jq(this);

                    
                        var searchedIndex = ($jq.inArray($jq(this).attr("data-author"), result.authors));
                        if(searchedIndex != -1){
                            actObj.val("Following");
                            actObj.removeClass("follow").addClass("following");
                        }                  
                    });                 
                }
            });
        }
    });
    
    
});


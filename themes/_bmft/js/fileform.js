(function($) {

$(function(){

    //Main url
    var dflt = {
            url2:  '/wp-content/themes/_ly/inc/mail_form/',
            speed: 'normal',
        };
        
    var formExe = {
    
    	errorOutput: function(p_class, out_text) {
            $('#main-form p.'+p_class).text(out_text).fadeIn(dflt.speed);
        },
    
    	errorMethod: function(switchBool) {
        
        	if(switchBool) { //trueだとerror処理をする
                //入力エラーの処理
                var name_compare = $('#nick_names').val() == '';
                var nameLength_compare = $('#nick_names').val().length > 20;
                var mail_compare = $('#mail_add').val() == '';
                var mailMatch_compare = $('#mail_add').val().match(/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/);
                
                
                if(name_compare || nameLength_compare || mail_compare || ! mailMatch_compare) {
                
                    if(name_compare) {
                        this.errorOutput('name-error', '『お名前』は必須です');   
                    }
                    else if(nameLength_compare) {
                        this.errorOutput('name-error', '『お名前』の文字数が多すぎます');
                    }
                    if (mail_compare) {
                        this.errorOutput('mail-error', '『メールアドレス』は必須です');
                    }
                    else { 
                        if(! mailMatch_compare) {
                            this.errorOutput('mail-error', '『メールアドレス』形式が不正です');
                        }
                    }
                    
                    this.errorOutput('all-error', '入力に誤りがあるようです。ご確認下さい。');
                }
                else { //エラー無しで以下動作
                    $("#main-form").fadeOut(dflt.speed, function(){
                        $("#report-form").css("display","block");
                    });
                }
            }
            else {
            	$("#main-form").fadeOut(dflt.speed, function(){
                	$("#report-form").css("display","block");
                });
            }
        },
        
        submitMethod: function() {
                
            // first submit
            $('#file_submit').click(function() {
            
                var fd = new FormData();
                
                if ($('#dl_file').val() != '') {
                    fd.append( "file", $('#dl_file').prop("files")[0] );
                }
                
                fd.append("p_id",$('input[name="p_id"]').val());
                

                //event.preventDefault();
                /* --> inputに『required』があるとブラウザデフォルト動作が入るので。（今は消している）
                要$('#submit-1').click(function(event) イベントリスナに引数eventを入れる*/ 
                
                //'<img style="vertical-align: middle;" src="'. get_template_directory_uri() .'/images/archive.png">'.'<span style="color:red;">' .
                
                /* Ajax通信 param type/url/data/success(function)>送信成功時 */
                $.ajax({
                    type: "POST",
                    dataType : "text",
                    url: '/wp-content/themes/_bmft/inc/handle-file/file-upload.php',
                    data: fd,
                    processData : false,
                    contentType : false,

                    success: function(data, dataType) {// successのブロック内 Ajax通信成功後の動作は以下
                    	//console.log(data);
                       if(data == 'ZIP-Error') {
                       	$('.wrap-first').prepend('<b style="color:red;">'+ data +' : please upload zip</b>');
                       }
                       else if(data == 'Error' ) {
                       	$('.wrap-first').prepend('<b style="color:red;">'+ data +' : upload failed</b>');
                       }
                       else {
                       		$('.wrap-first').find('b').remove();
                       		$('.wrap-first > div > span').html(data);
                       		$('.wrap-first > div').fadeIn(200);
                       		$('#dl_file+label').hide();
                       		$('#file_name').attr('value', data);
                       }
                    },//1st success
                    
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('.wrap-first > div').html('送信出来ませんでした。'+ errorThrown);
                       
                        //alert('Error : ' + errorThrown);
                    }//1st error
                    
                });//1st ajax < submit-1
                
                
                
                return false;
                
            });//submit-1 >click

            //});//all-form load この中に全ての動作を入れないとうまく動作しない
    	}, //submitMethod
  
        deleteMethod: function() {
                
                // first submit
                $('#del_sub').click(function() {
                	
                    var th = $(this);
                    var fd = new FormData();

                    fd.append("file_name", $("#file_name").val());
                    fd.append("post_id", $('input[name="post_id"]').val());

                    /* Ajax通信 param type/url/data/success(function)>送信成功時 */
                    $.ajax({
                        type: "POST",
                        dataType : "text",
                        url: '/wp-content/themes/_bmft/inc/handle-file/file-delete.php',
                        data: fd,
                        processData : false,
                    	contentType : false,

                        success: function(data, dataType) {// successのブロック内 Ajax通信成功後の動作は以下
                           $('.wrap-first > div').css({display:'none'});
                           //$('#del_form').css({display:"none"});
                        },//1st success
                        
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $('.wrap-first > div').html('送信出来ませんでした。'+ errorThrown);
                           
                            //alert('Error : ' + errorThrown);
                        }//1st error
                        
                    });//1st ajax < submit-1
                    
                    
                    
                    return false;
                    
                });//submit-1 >click

            //});//all-form load この中に全ての動作を入れないとうまく動作しない
    	}, //deleteMethod
    
    } //var formExe
    
    formExe.submitMethod();
    formExe.deleteMethod();
    
}); //document.ready

})(jQuery);

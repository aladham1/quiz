var base_url = $('.base_url').val();

function searchGroup(val){
    if(val!=''){
        $('.grp_list').each(function(){
            if($(this).attr('data').toLowerCase().indexOf(val.toLowerCase()) != -1){
                $(this).show();
            }else{
                $(this).hide();
            }
        })
    }else{
        $("#grp_list").load(location.href + " #grp_list");
    }
}

function pubslihedToGroup(publishroute){
    var xmid = localStorage.getItem("publishXmId");
    var gid = localStorage.getItem("publishGroup");
    var password = $('.publish_password').val();
    $.post(publishroute,{new_exams:[xmid],gid:gid,password:password, _token: $('meta[name="csrf-token"]').attr('content')},function(rs){ console.log(rs);
        $('.mask').click();
        if(rs==1){
            swal.fire("Success","Exam published","success");
        }else{
            swal.fire("Error",rs,"error");
        }
    })
}

function changeList(list, selector){
    var name = list == '#deletedList' ? 'deleted_exams[]' : list == '#newlyaddedList' ? 'new_exams[]' : list == '#existingList' ? 'exams[]' : '';
    var span = list == '#deletedList' ? '<span class="gqlDlt" style="background: unset; font-size:unset; height: unset;" onclick="changeList(\'#originalParentList\', \'#xmlst_idhere\')"><i class="fas fa-trash-restore-alt"></i></span>' : '<span class="gqlDlt " onclick="changeList(\'#deletedList\', \'#xmlst_idhere\')">X</span>';
    $(selector).each(function(){
        console.log($(this));
        if($(this).prop("checked")==true || "#" + $(this).attr('id') == selector){
            var lstid = $(this).parent().parent().attr('id');
            var xmid = $(this).val();
            $(this).next('label').next('span').remove();
            $(this).next('label').after(span.replace('originalParentList',lstid).replace('idhere', xmid));
            $(this).attr('name', name);
            list == '#deletedList' ? $(this).removeClass('deletable').addClass('restorable') : $(this).addClass('deletable').removeClass('restorable');
            $(this).parent().appendTo(list);
            $(this).attr('id', 'xmlst_'+xmid);
            $(this).next('label').attr('for', 'xmlst_'+xmid);
        }
    });
    selector == '.gqList.all_exams .xm_list' ? $('#grpStPop').fadeOut() : false;

}

function addExamToGroup(){
    var all_exam = [];
    var htmls = '';
    $('.xm_list').each(function(){
        if($(this).prop("checked")==true){
            all_exam.push($(this).attr("value"));
            var xmname = $(this).attr('xmname');
            var xmimg = $(this).attr('xmimg');
            var id = $(this).attr('value');
            var xmid = (parseInt($(this).attr('value')) + 1000 );
            htmls += '<li class="removeXm_'+id+'"> <span class="gqlDlt" onclick="rmExm('+id+')">X</span>'+
                        '<label>'+
                            '<div class="lblIn">'+
                                '<div class="gqlImg"><img src="'+xmimg+'"/></div>'+
                                '<div class="gqlT1">CODE: <b>'+xmid+'</b></div>'+
                                '<div class="gqlT2">'+xmname+'</div>'+
                            '</div>'+
                        '</label>'+
                    '</li>';
        }
    })

    $('.userSelectGrp').append(htmls);
    var all_xm = all_exam.join();
    $('.exam_list_val').val(all_xm);

    console.log(all_exam);
    $('#grpStPop').fadeOut();
}

function publishExamToGroup(xmid){
    // $( ".gpOpn" ).click(function() {
        $( "#mask" ).fadeIn();
        $( "#togroup" ).addClass( "open" );
        localStorage.setItem("publishXmId",xmid);
    //   });

}

function removeExamFromGroup(gid,id){
    var conf = confirm("Are you sure?");
    if(conf==true){
        $.post(base_url+"removeExamFromGroup",{gid:gid,id:id},function(rs){
            if(rs==1){
                $('.xmrm_'+id).remove();
                iqwerty.toast.toast("Exam Removed");
            }else{
                iqwerty.toast.toast("Something went wrong!");
            }
        })
    }
}

function showGroupDetails(gurl){
    axios.get(gurl, {headers: { 'content-type': 'application/json' }})
        .then(function(rs){
        // $( ".gDtlOpn" ).click(function() {
            var uid = quser_id;
            console.log(rs);
            var group = rs.data.group;
            console.log(group.owner);
            var dp = base_url+'/images/prf.png';
            if(group.owner.avatar!='' && group.owner.avatar!=null){
                dp = group.owner.avatar;
            }
            $('.owner_img').attr("src",dp);
            $('.owner_name').text(group.owner.name);
            $('.create_date').text(group.created_at);
            $('.total_quest').text(group.exams_count);
            $('.total_follower').text(group.followers_count)

            var grp_img = base_url+"/images/placeholder.jpeg";
            if(group.image!='' && group.image!=null){
                grp_img = group.image;
            }
            $('.grp_img').attr("src",grp_img);
            $('.group_name').text(group.title +' - '+group.id);

            if(group.owner.id== uid){
                $('.gpsswrdArea').hide();
            }else{
                $('.gpsswrdArea').show();
            }

            toGrplose();
            $( "#mask" ).fadeIn();
            $( "#togdtl" ).addClass( "open" );
        //   });

        localStorage.setItem("publishGroup",group.id);

    })
}

function unfollowGroupThis(gid,myid,obj){
    var conf = confirm("Are you sure?");
    if(conf==true){
        axios.post(unfollow_cmd_link, {group:gid})
        .then(function(rs){

                $(obj).attr("style","background:#F232A4; color: #fff; border: 1px solid #F232A4");
                $(obj).removeAttr("onclick");
                $(obj).attr("onclick","followGroupThis("+gid+","+myid+",this)");
                $(obj).text("FOLLOW");

                Swal.fire({
                    toast: true,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 4000,
                    title: 'unfollowed',
                    icon: 'warning',
                });
        })
        .catch(function (err) {
            swal("Error","Something went wrong!\n"+err,"error");
        });
    }
}

function followGroupThis(gid,myid,obj){
    axios.post(follow_cmd_link ,{group:gid})
    .then(function(rs){
            $(obj).removeAttr("style");
            $(obj).removeAttr("onclick");
            $(obj).attr("onclick","unfollowGroupThis("+gid+", "+myid+", this)");
            $(obj).text("UNFOLLOW");
            Swal.fire({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 4000,
                title: 'followed',
                icon: 'success',
            });
    })
    .catch( function(err) {
        console.log(err);
    });
}

function UpdateGroup(){
    var gid = $('.group_id').val();
    var grp_img = $('.group_image').val();
    var title = $('.group_title').val();
    //var group_parent = $('.group_parent').val();
    var group_info = $('.group_info').val();
    var group_pass = $('.group_pass').val();
    var group_tags = $('.group_tags').val();
    var is_private = 0;
    if($('#grivate').prop("checked")==true){
        is_private = 1;
    }
    var exams = $('.exam_list_val').val();

    if(title=='' || group_info=='' || group_pass=='' || group_tags==''){
        swal.fire("Warning","All fields are mandatory","warning");
    }else{
        var form_data = new FormData();
        form_data.append("grp_img",grp_img);
        form_data.append("title",title);
        //form_data.append("group_parent",group_parent);
        form_data.append("group_info",group_info);
        form_data.append("group_pass",group_pass);
        form_data.append("group_tags",group_tags);
        form_data.append("is_private",is_private);
        form_data.append("exams",exams);
        form_data.append("gid",gid);
        showLoader();
        $.ajax({
            url: base_url+"/UpdateGroup",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData:false,
            success: function(rs){
                console.log(rs);
                if(rs==1){
                    window.location.href=base_url+'/profile';
                }else{
                    hideLoader();
                    swal.fire("Error","Something went wrong!","error");
                }
            }
        })
    }
}

function saveGroup(){

    var grp_img = $('.group_image').val();
    //var grp_img = $('.group_img').prop("files")[0];
    var title = $('.group_title').val();
    //var group_parent = $('.group_parent').val();
    var group_info = $('.group_info').val();
    var group_pass = $('.group_pass').val();
    var group_tags = $('.group_tags').val();
    var is_private = 0;
    if($('#grivate').prop("checked")==true){
        is_private = 1;
    }
    var exams = $('.exam_list_val').val();

    if(title=='' || group_info=='' || group_pass=='' || group_tags==''){
        swal.fire("Warning","All fields are mandatory","warning");
    }else{
        var form_data = new FormData();
        form_data.append("grp_img",grp_img);
        form_data.append("title",title);
        //form_data.append("group_parent",group_parent);
        form_data.append("group_info",group_info);
        form_data.append("group_pass",group_pass);
        form_data.append("group_tags",group_tags);
        form_data.append("is_private",is_private);
        form_data.append("exams",exams);
        showLoader();
        $.ajax({
            url: "saveGroup",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData:false,
            success: function(rs){
                console.log(rs);
                if(rs==1){
                    window.location.href=base_url;
                }else{
                    hideLoader();
                    swal.fire("Error","Something went wrong!","error");
                }
            }
        })
    }
}

function searchGroupDiscover(val){
    if(val==''){
        revertHtml();
    }else{
        axios.get(groups_search_link+'?query='+val)
        .then(function(rs){
            console.log(rs.data);
            var obj = rs.data;
            var html = '';
            for(var i=0;i<obj.length;i++){
                var follow_html ='';
                if(obj[i].following){
                    follow_html = '<span class="unflwBtn" onclick="unfollowGroupThis('+obj[i].id+', 1, this)">UNFOLLOW</span>';
                }else{
                    follow_html = '<span class="unflwBtn" style="background:#F232A4; color: #fff; border: 1px solid #F232A4" onclick="followGroupThis('+obj[i].id+', 2, this)">FOLLOW</span>';
                }
                html += '<li class="rmvGrp_">'+
                            '<div class="flowBx1">'+

                                '<div class="qsRow1 q4c">'+
                                    '<div class="flowBx5">'+
                                    '<a href="'+group_details_link.replace('#group#', obj[i].id)+'"> <span style="right:130px" class="clqBx"></span> </a>'+

                                        '<img src="'+obj[i].image+'" style="width:100%">'+
                                    '</div>'+
                                    '<div class="flowBx2">'+
                                        '<div class="qsTxt2 q4a">'+
                                            'CODE: <b>'+obj[i].id+'</b> <span class="q4Pass">Creator: <b>'+obj[i].owner.name+'</b></span>'+
                                        '</div>'+
                                        '<div class="flowBx3">'+
                                            obj[i].title+
                                        '</div>'+



                                    '<div class="s7btm">'+
                                    '<aside class="flowBx4">'+
                                        '<b>'+obj[i].exams_count+'</b> Quest <br/>'+
                                        '<b>'+obj[i].followers_count+'</b> Followers'+
                                    '</aside>'+

                                    '<aside class="setRnew q4e">'+

                                        follow_html+
                                        '<span class="btnIc q4Dot"><img src="'+base_url+'/images/dot_pink.svg">'+

                                        '</span>'+
                                    '</aside>'+
                                    '</div>'+

                                '</div>'+


                                '</div>'+

                            '</div>'+
                        '</li>';
            }
            $('.quest_list').html(html);
            $('.msksbr').hide();

            $('.qsLst').show();
            $('.qsLst1').show();

            $('.rcmPLst').hide();
            $('.flwngList').hide();
        })
        .catch( function(err) {
            console.log(err);
        });
    }
}

function revertHtml(){
    $('.quest_list').html('');
    $('.qsLst').hide();

    $('.rcmPLst').show();
    $('.flwngList').show();
}

function removeExamFromGroup(exam_id,group_id){
    var conf = confirm("Are you sure?");
    if(conf==true){
        $.post(base_url+"/removeExamFromGroupInfoPg",{exam_id:exam_id,group_id:group_id},function(rs){ console.log(rs);
            if(rs==1){
                $('.rmv_group_'+group_id).fadeOut();
            }else if(rs==2){
                iqwerty.toast.toast('Unauthorized access');
            }else{
                swal.fire("Error","Something went wrong!","error");
            }
        })
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            // $('#blah')
            //     .attr('src', e.target.result)
            //     .width(150)
            //     .height(200);

            $('.group_img').val('');
            var tmpImg = new Image();
            tmpImg.src=e.target.result; //or  document.images[i].src;
            $(tmpImg).one('load',function(){
                $('#canvasArea').attr("height",tmpImg.height);
                $('#canvasArea').attr("width",tmpImg.width);
            });


            OpenCroperPop('1',e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function clickImg(){
$('.group_img').click();
}

function closePop(){
    $('.rmWhtPop').fadeOut();
    $('.rmMask').fadeOut();
}

function showPopGropInfo(url){
//axios.get(url).
//    then(function(rs){
    $('.rmMask').fadeIn();
    //$('.grpInfo').html(rs.data);
    $('.rmWhtPop').fadeIn();
//})


}

function showTab(obj,clas){
    $('.actv').removeClass('actv');
    if(clas=='follower'){
        $('.flwLsts').show();
        $('.nhLst').hide();
        $('.nwsLst').hide();
        $(obj).addClass('actv');
    }else if(clas=='news'){
        $('.flwLsts').hide();
        $('.nhLst').hide();
        $('.nwsLst').show();
        $(obj).addClass('actv');
    }else{
        $('.nhLst').show();
        $('.flwLsts').hide();
        $('.nwsLst').hide();
        $(obj).addClass('actv');
    }
}

$('.hloader').on('inview', function(event, isInView) {
if (isInView) {

    var nextPage = parseInt($('#h_pageno').val())+1;
    var gid = '<?=$id?>';
        $.ajax({
            type: 'POST',
            url: base_url+'/getFollower',
            data: {
                pageno: nextPage,
                gid:gid
            },
            success: function(data){ console.log("data received",data);
                if(data != ''){
                    $('.follwer_list').append(data);
                    $('#h_pageno').val(nextPage);
                } else {
                    $('.hloader').hide();
                }
            }
        });
    }
});


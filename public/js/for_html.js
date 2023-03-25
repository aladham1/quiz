function screensize() {
    var wndow_ht = $(window).height();
    //$('body').css("min-height",wndow_ht);

    var myPopPage_ht = $(".myPopPage").height();
    var top_cal = (wndow_ht - myPopPage_ht) / 2;
    $(".myPopPage").css("top", top_cal);

    /*
if(wndow_ht<aboutBox_ht) {
	$('.myPopPage').css("top","10px");
	$('.myPopPage').css("padding","0px 0px 10px 0px");
}
*/
}

$(document).ready(function() {
    screensize();

    var rows = document.getElementById("rows");
    var rowsoutput = document.getElementById("rowsv");
    if (rows != null) {
        rowsoutput.innerHTML = rows.value;

        rows.oninput = function() {
            rowsoutput.innerHTML = this.value;
        };
    }

    var columns = document.getElementById("columns");
    var columnsoutput = document.getElementById("columnsv");
    if (columns != null) {
        columnsoutput.innerHTML = columns.value;
        columns.oninput = function() {
            columnsoutput.innerHTML = this.value;
        };
    }
});
$(window).resize(function() {
    screensize();
});
$(window).on('load', function() {
    screensize();
});

//popP1
function ExamOption() {
    $("#mask").fadeIn();
    $("#popP1").addClass("open");
}

function popp1close() {
    $("#popP1").removeClass("open");
    $("#popP2").removeClass("open");
    $("#mask").fadeOut();
}

function showLoader() {
    $(".loader").show();
}
function hideLoader() {
    $(".loader").hide();
}

//D&D question

// Drag & Drop Popup ddPbtn
$("#ddPbtn").click(function() {
    $("#ddPop").fadeIn();
    popp1close();
});
function ddclose() {
    $("#ddPop").fadeOut();
}

//word game
function countRemainWord(word, max, classnm) {
    console.log("length ", word.length);
    var remain_txt = parseInt(max - parseInt(word.length));
    $("." + classnm).text("* Letter Remains " + remain_txt);
}
//popP2
function ExamOption2() {
    $("#mask").fadeIn();
    $("#popP2").addClass("open");
}

function openQuestPop(type) {
    if (type == "word_game") {
        $("#wgmPop").fadeIn();
        popp1close();
    } else if (type == "multiple_choice") {
        $("#mchPop").fadeIn();
        popp1close();
    } else if (type == "project") {
        $("#prjPop").fadeIn();
        popp1close();
    } else if (type == "dd") {
        $("#ddPop").fadeIn();
        popp1close();
    }
}

function openQuestPopEdit(type) {
    if (type == "word_game") {
        $("#wgmPop").fadeIn();
        $(".wgSvBtn").attr("onclick", "saveWordGameQuestEdit()");
        popp1close();
    } else if (type == "multiple_choice") {
        $("#mchPop").fadeIn();
        popp1close();
        $(".mcq_svBtn").attr("onclick", "saveMultipleChoiceQuestEdit()");
    } else if (type == "project") {
        $("#prjPop").fadeIn();
        $(".pr_svBtn").attr("onclick", "saveProjectEdit()");
        popp1close();
    }
}

// Project popup Box prjPop

function prjPclose() {
    $("#prjPop").fadeOut();
}

function closeQuestPop(type) {
    //reset all value
    //reset all data
    tmpChecker('_tmp', false)
    .then(function() {
        temp_question_media_edits = [];
        tmptype = false;
        editing_model = false;
        $(".quest_image").val("");
        $(".quest_video").val("");
        $(".quest_audio").val("");

        if (type == "word_game") {
            $(".word_game_title").val("");
            $(".word_game_answer").val("");
            $(".wg_img_list").html("");
            $(".wgImgBx").hide();
            $(".wgaudTag").html("");
            $(".wgAudBx").hide();
            $(".wgvdoTag").html("");
            $(".wgVdoBx").hide();

            $(".wgSvBtn").attr("onclick", "saveWordGameQuest()");

            $("#popP2").removeClass("open");
            $("#wgmPop").fadeOut();
            $("#mask2").fadeOut();
        } else if (type == "multiple_choice") {
            //reset all popup value
            $(".multiple_question").val("");
            $(".quest_image").val("");
            $(".quest_audio").val("");
            $(".quest_video").val("");

            $(".mc_img_list").html("");
            $(".mcImgBx").hide();

            $(".quest_image_option_1").val("");
            $(".quest_image_option_2").val("");
            $(".quest_image_option_3").val("");
            $(".quest_image_option_4").val("");

            $(".qst_ans_1").val("");
            $(".qst_ans_2").val("");
            $(".qst_ans_3").val("");
            $(".qst_ans_4").val("");

            //question html remove
            $(".mcvdoTag").html("");
            $(".mcVdoBx").hide();
            $(".mcaudTag").html("");
            $(".mcAudBx").hide();
            $(".mc_img_list").html("");
            $(".mcImgBx").hide();
            //remove html prview
            $(".ansImgArea_1").html("");
            $(".ansImgArea_1").hide();
            $(".ansAud_1").html("");
            $(".ansAud_1").hide();
            $('input[type=radio][name="sml_rdio"][value="text"]').prop(
                "checked",
                true
            );
            $(".ansTxt_1").show();

            $(".ansImgArea_2").html("");
            $(".ansImgArea_2").hide();
            $(".ansAud_2").html("");
            $(".ansAud_2").hide();
            $('input[type=radio][name="sml_rdio2"][value="text"]').prop(
                "checked",
                true
            );
            $(".ansTxt_2").show();

            $(".ansImgArea_3").html("");
            $(".ansImgArea_3").hide();
            $(".ansAud_3").html("");
            $(".ansAud_3").hide();
            $('input[type=radio][name="sml_rdio3"][value="text"]').prop(
                "checked",
                true
            );
            $(".ansTxt_3").show();

            $(".ansImgArea_4").html("");
            $(".ansImgArea_4").hide();
            $(".ansAud_4").html("");
            $(".ansAud_4").hide();
            $('input[type=radio][name="sml_rdio4"][value="text"]').prop(
                "checked",
                true
            );
            $(".ansTxt_4").show();
            $(".mcq_svBtn").attr("onclick", "saveMultipleChoiceQuest()");

            $("#mchPop").fadeOut();
            $("#popP2").removeClass("open");
            $("#mask2").fadeOut();
            $("#mask1").fadeOut();
        } else if (type == "project") {
            $(".project_title").val("");
            project_editor.setData("");
            $(".pr_img_list").html("");
            $(".prImgBx").hide();
            $(".praudTag").html("");
            $(".prAudBx").hide();
            $(".prvdoTag").html("");
            $(".prVdoBx").hide();
            $(".pr_svBtn").attr("onclick", "saveProject()");

            $("#prjPop").fadeOut();
            $("#popP2").removeClass("open");
            $("#mask2").fadeOut();
            $("#mask").fadeOut();
        } else if (type == "dd") {
            $("#ddPop").fadeOut();
            $('#puzzle_name').val('');
            counter = 0;
            active_selector = '';
            puzzle_keys = {};
            $('#puzzle').attr('type', 'text');
            $('#puzzle').attr('type', 'file');
            document.getElementById('keys').textContent = '';
            layer.removeChildren();
            layerback.removeChildren();
            layerback.add(selectionRectangle);
            ds_ctx.clearRect(0, 0, ds.width, ds.height);
            for(var rm=1;rm<=4;rm++){
                $('.ddTgAnsTxt_'+rm).val('');
                $('.ddTgAnsImg_'+rm).val('');
                $('.crpTrg'+rm).html('<img src="'+rootURL+'images/image.svg" />');
                $('.target_'+rm).val('');
                $('.target_'+rm+'_imgdata').val('');
                $('.ddTrgtLi_'+rm).removeClass("added");
            }

            //removeDDQsnImg();
            $(".dd_question").val("");
            $(".quest_image").val("");

            $(".ddsaveQsn").attr("onclick", "saveDDQuestion()");
        }
    })

}

function closeQuestPopEdit(type) {
    closeQuestPop(type);
}

function mchclose() {
    $("#mchPop").fadeOut();
}

function wgamelose() {
    $("#wgmPop").fadeOut();
}

//question page

function vdopopclose() {
    $("#vdoPop").fadeOut();
}

function titlepopclose() {
    $("#titlPop").fadeOut();
}

function orderpopclose() {
    $("#orderPop").fadeOut();
}

function openIntroPop(type, index = "") {
    if (type == "title") {
        $("#titlPop").fadeIn();
        popp1close();
        $("#mask").fadeIn();
    } else if (type == "video") {
        $("#vdoPop").fadeIn();
        $(".video_svbtn").attr("onclick", "saveDataPopIntro('video')");
        popp1close();
        $("#mask").fadeIn();
    } else if (type == "audio") {
        $("#audPop").fadeIn();
        var record = $(".audio_svbtn").data('record');
        $(".audio_svbtn").attr("onclick", "saveDataPopIntro('audio','',true, "+record+")");
        popp1close();
        $("#mask").fadeIn();
    } else if (type == "table") {
        $("#tablePop").fadeIn();
        generateTable();
        popp1close();
    } else if (type == "image") {
        $(".introImg").click();
    } else if (type == "paragraph") {
        $("#prgPop").fadeIn();
        $(".prgBx textarea").focus();
        paragraph_editor.setData("");
        popp1close();
    } else if (type == "file") {
        $(".introFile").click();
    } else if (type == "quest_wg_vid") {
        // question word game video

        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr("onclick", "saveDataPopIntro('quest_wg_video')");
    } else if (type == "quest_wg_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr("onclick", "saveDataPopIntro('quest_wg_audio')");
    } else if (type == "quest_mc_vid") {
        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr("onclick", "saveDataPopIntro('quest_mc_video')");
    } else if (type == "quest_mc_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr("onclick", "saveDataPopIntro('quest_mc_audio')");
    } else if (type == "quest_qo_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr(
            "onclick",
            "saveDataPopIntro('quest_qo_audio'," + index + ")"
        );
    } else if (type == "quest_pr_vid") {
        if ($('.prVdoBx iframe').length > 0 && $('.prVdoBx').css('display') == 'none'){
            var cls = '.pr';
            $(cls + 'AudBx').slideUp();
            $(cls + 'ImgBx').slideUp();
            $(cls + 'VdoBx').slideDown();
            return;
        }
        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr("onclick", "saveDataPopIntro('quest_pr_video')");
    } else if (type == "quest_pr_audio") {
        if ($('.praudTag audio').length > 0 && $('.prAudBx').css('display') == 'none'){
            var cls = '.pr';
            $(cls + 'VdoBx').slideUp();
            $(cls + 'ImgBx').slideUp();
            $(cls + 'AudBx').slideDown();
            return;
        }
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr("onclick", "saveDataPopIntro('quest_pr_audio')");
    } else if (type == "projects_video") {
        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr("onclick", "saveDataPopIntro('projects_video')");
    } else if (type == "projects_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr("onclick", "saveDataPopIntro('projects_audio')");
    } else if (type == "projects_image") {
        $(".projectImg").click();
    } else if (type == "order") {
        $("#orderPop").fadeIn();
        popp1close();
        $("#mask").fadeIn();
    }
}

function openIntroPopEdit(type, index = "") {
    if (type == "title") {
        $("#titlPop").fadeIn();
        popp1close();
        $("#mask").fadeIn();
        $(".title_svbtn").attr("onclick", "saveDataPopIntroEdit('title')");
    } else if (type == "video") {
        $("#vdoPop").fadeIn();
        $(".video_svbtn").attr("onclick", "saveDataPopIntroEdit('video')");
        popp1close();
        $("#mask").fadeIn();
    } else if (type == "audio") {
        $("#audPop").fadeIn();
        $(".audio_svbtn").attr("onclick", "saveDataPopIntroEdit('audio')");
        popp1close();
        $("#mask").fadeIn();
    } else if (type == "table") {
        $("#tablePop").fadeIn();
        generateTable();
        $(".table_svbtn").attr("onclick", "saveDataPopIntroEdit('table')");
        popp1close();
    } else if (type == "image") {
        $(".introImg").click();
    } else if (type == "paragraph") {
        $("#prgPop").fadeIn();
        $(".prgBx textarea").focus();
        myEditor.setData("");
        $(".paragraph_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('paragraph')"
        );
        popp1close();
    } else if (type == "file") {
        $(".introFile").click();
    } else if (type == "quest_pr_file") {
        $(".projectFile").click();
    } else if (type == "quest_wg_vid") {
        // question word game video

        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_wg_video')"
        );
    } else if (type == "quest_wg_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_wg_audio')"
        );
    } else if (type == "quest_mc_vid") {
        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_mc_video')"
        );
    } else if (type == "quest_mc_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_mc_audio')"
        );
    } else if (type == "quest_qo_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_qo_audio'," + index + ")"
        );
    } else if (type == "quest_pr_vid") {
        $("#vdoPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        //change Button Save function
        $(".video_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_pr_video')"
        );
    } else if (type == "quest_pr_audio") {
        $("#audPop").fadeIn();
        popp1close();
        $("#mask2").fadeIn();
        $(".audio_svbtn").attr(
            "onclick",
            "saveDataPopIntroEdit('quest_pr_audio')"
        );
    } else if (type == "order") {
        $("#orderPop").fadeIn();
        popp1close();
        $("#mask").fadeIn();
        $(".order_svbtn").attr("onclick", "saveDataPopIntroEdit('order')");
    }
}

//answer section
function showAnsArea(index, type) {
    if (type == "text") {
        $(".ansTxt_" + index).show();

        $(".ansImg_" + index).hide();
        $(".ansAud_" + index).hide();
        $(".ansImgArea_" + index).html("");
    } else if (type == "image") {
        $(".ansImg_" + index).click();

        //
    } else if (type == "audio") {
        openIntroPop("quest_qo_audio", index);
        //$('.ansAud_'+index).show();

        $(".ansTxt_" + index).hide();
        $(".ansImg_" + index).hide();
        $(".ansImgArea_" + index).html("");
    }
}
//reward page

function showRewardMode(val) {
    if (val == 0) {
        $(".singleModeRew").slideDown();
        $(".list_coupon").slideUp();
    } else {
        $(".singleModeRew").slideUp();
        $(".list_coupon").slideDown();
    }
}

function showRewardMsg(val) {
    if (val == 0) {
        $(".ble_reward").slideDown();

        $(".text_reward").slideUp();
        $(".image_reward").slideUp();
        $(".video_reward").slideUp();
        $(".cer_reward").slideUp();
    } else if (val == 1) {
        $(".text_reward").slideDown();

        $(".ble_reward").slideUp();
        $(".image_reward").slideUp();
        $(".video_reward").slideUp();
        $(".cer_reward").slideUp();
    } else if (val == 2) {
        $(".image_reward").slideDown();

        $(".ble_reward").slideUp();
        $(".video_reward").slideUp();
        $(".cer_reward").slideUp();
        $(".text_reward").slideUp();
    } else if (val == 3) {
        $(".video_reward").slideDown();

        $(".ble_reward").slideUp();
        $(".cer_reward").slideUp();
        $(".text_reward").slideUp();
        $(".image_reward").slideUp();
    } else if (val == 4) {
        $(".cer_reward").slideDown();

        $(".ble_reward").slideUp();
        $(".text_reward").slideUp();
        $(".image_reward").slideUp();
        $(".video_reward").slideUp();
    }
}
//home page
function openGEPref() {
    console.log("clicking");
    $("#mask").fadeIn();
    $("#popP1").addClass("open");
}
function popp1close() {
    $("#popP1").removeClass("open");
    $("#popP2").removeClass("open");
}

function prgpclose() {
    $("#prgPop").fadeOut();
}

function audiopclose() {
    $("#audPop").fadeOut();
}

// Table popup tablePop

function tablepclose() {
    $("#tablePop").fadeOut();
}

function showGroupExamPop() {
    $("#grpStPop").fadeIn();
}

function closeGrpExam() {
    $("#grpStPop").fadeOut();
}

//CLOSE
function allpclose() {
    $("#mask").fadeOut();
    $("#mask2").fadeOut();
    vdopopclose();
    popp1close();
    titlepopclose();
    orderpopclose();
    audiopclose();
    tablepclose();
    prgpclose();

    toGrplose();
    togdtlclose();
}
$("#mask").click(function() {
    allpclose();
});
$("#mask2").click(function() {
    allpclose();
});
$(".pcancel").click(function() {
    //     if (typeof timer != "undefined") {
    //     clearInterval(timer);
    //     // rec.stop();
    //     gumStream.getAudioTracks()[0].stop();
    //     // rec.clear();
    //     $(".aud_timer").text("00 : 00");
    //     $(".audIcnBtn").attr("onclick", "startRecording()");
    // }
    allpclose();
});

function tabMove(obj, val) {
    if (val == "my_quest") {
        $(".gpSetHS").hide();

        $(".xmSetHS").show();
        $(".hstryTab").hide();
        $(".actv").removeClass("actv");
        $(obj).addClass("actv");
    } else if (val == "history") {
        $(".xmSetHS").hide();
        $(".gpSetHS").hide();

        $(".hstryTab").show();

        $(".actv").removeClass("actv");
        $(obj).addClass("actv");
    } else if (val == "my_group") {
        $(".gpSetHS").show();

        $(".xmSetHS").hide();
        $(".hstryTab").hide();
        $(".actv").removeClass("actv");
        $(obj).addClass("actv");
    } else if (val == "projects") {
        $(".projectTab").show();

        $(".gpSetHS").hide();
        $(".hstryTab").hide();
        $(".xmSetHS").hide();
        $(".actv").removeClass("actv");
        $(obj).addClass("actv");
    }
}

$("body").on("click", ".mnuicn", function() {
    //$( ".mnuicn" ).click(function() {
    $(".mnuMsk").fadeIn();
    $(".sidMnu").addClass("opn");
});
$("body").on("click", ".mnuCls", function() {
    //$( ".mnuCls" ).click(function() {
    $(".mnuMsk").fadeOut();
    $(".sidMnu").removeClass("opn");
});

$("body").on("click", ".rewMsk", function() {
    //$( ".rewMsk" ).click(function() {
    $(".rewPop").fadeOut();
    $(".rewMsk").fadeOut();
});

$("body").on("click", ".rBtnsrch", function() {
    //$( ".rBtnsrch" ).click(function() {
    $(".msksbr").fadeIn();
    $(".srcBr").addClass("opn");
});

$("body").on("click", ".msksbr", function() {
    //$( ".msksbr" ).click(function() {
    $(this).fadeOut();
    $(".srcBr").removeClass("opn");
});

function startTimer(duration, display, total) {
    var timer = duration,
        minutes,
        seconds;
    setInterval(function() {
        //get remaining percentage
        percentage = (timer * 100) / duration;
        //remain = (100 - percentage);
        $(".prgBr").css("width", percentage + "%");
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        if (minutes == 0 && seconds == 0) {
            //finish exam
            gotonext(total, total);
        }
        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

var svgContainer = document.getElementById("bm");
// var animItem = bodymovin.loadAnimation({
//     wrapper: svgContainer,
//     animType: "svg",
//     loop: true,
//     path: "https://assets2.lottiefiles.com/packages/lf20_BNm2Zk.json"
// });

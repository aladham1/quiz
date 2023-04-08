//Global vars

//the offline DB
var questions = localforage.createInstance({
    // name: offlineDBname
});


//the index names class for request paramters which are also used as index names for the offline DB
var request_names = {
    current_exam: 'Exam1_'
};

var base_url = $('.base_url').val();

function isJSON(str) {
    try {
        return JSON.parse(str);
    } catch (e) {
        return false
    }
}

function getId(url) {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);

    if (match && match[2].length == 11) {
        return match[2];
    } else {
        return 'error';
    }
}

function getDuration(src, cb) {
    var audio = new Audio();
    $(audio).on("loadedmetadata", function () {
        cb(audio.duration);
    });
    audio.src = src;
}

function restoreExam() {
    var examObj = {};
    Promise.all([questions.getItem('Exam'),
        questions.getItem('Exam_icon'),
        questions.getItem('Exam_reward-image', rew_image),]
    )
        .then(function (res) {
            examObj = res[0];
            exam_data(res[0]);
            exam_icon(res[1]);
            exam_reward_image(res[2]);
        })
        .catch(function (err) {
            console.log(err);
        })

    function exam_data(res) {
        if (res != null) {
            $('.exam_title').val(res.title);
            res.preq = isJSON(res.preq) || res.preq;
            $('.preq_type').val(res.preq != undefined ? res.preq.type : '0');
            if (res.preq.type != 0) getExtraPreqField(res.preq.type);
            $('.preq_value').val(res.preq != undefined ? res.preq.value : '');
            $('input[type="checkbox"][name="is_random"]').prop('checked', res.random);
            $('input[type="checkbox"][name="is_retake"]').prop('checked', res.retake);
            $('input[type="checkbox"][name="is_chat"]').prop('checked', res.chat);
            if (res.time_limit == -1) {
                $('input[type="checkbox"][class="timeLimit"]').prop('checked', false);
            } else {
                $('input[type="checkbox"][class="timeLimit"]').prop('checked', true);
                showtmBx();
                $('.tmBx').val(res.time_limit);
            }
            $('input[type="checkbox"][name="is_private"]').prop('checked', res.private);
            $('input[type="checkbox"][name="is_login_required"]').prop('checked', res.login_required);
            if (res.login_required) {
                toggleFieldsBox();
                $('.infld2.login_fields').val(res.login_fields);
            }
            $('.rev_type').val(res.review_type || 0);
            $('.count').val(res.question_count || 0);
            $('.pass_percentage').val(res.pass_percentage || 0);
            $('.rew_mod').val(res.reward_mode || 0);
            $('.coupon_list').val(res.coupon_list || 0);
            $('.rew_type').val(res.reward_type || 0);
            $('.hardware_name').val(res.hardware_name || 0);
            $('.charectar').val(res.special_control_char || 0);
            $('.rew_text').val(res.reward_message || 0);
            $('.rew_video').val(res.reward_video || 0);
            $('.cert_lang').val(res.cert_lang || 0);
            showRewardMsg(res.reward_type);
        }
    }

    function exam_icon(res) {
        if (res != null) {
            var img = URL.createObjectURL(res);
            $('.imgInp_hidden').val('Exam_icon');
            $('#blah').on('load', function () {
                URL.revokeObjectURL(img)
            });
            $('#blah').attr('src', img);
        } else {
            $('.imgInp_hidden').val('Exam_icon');
            axios.get(getFileURL.replace('file_path', examObj['icon']))
                .then(function (res) {
                    $('#blah').attr('src', res.data);
                });

        }
    }

    function exam_reward_image(res) {
        if (res != null) {
            var img = URL.createObjectURL(res);
            $('#showReward').on('load', function () {
                URL.revokeObjectURL(img)
            });
            $('#showReward').attr('src', img);
        } else {
            axios.get(getFileURL.replace('file_path', examObj['reward_image']))
                .then(function (res) {
                    $('#showReward').attr('src', res.data);
                });
        }
    }
}

function restoreQuestions(id, res, originalOrder) {
    var type = id.includes('Project') ? 'project' : id.includes('WordGame') ? 'wg' : id.includes('MultipleChoiceQuestion') ? 'mcq' : 'dd';
    var text = id.includes('Project') ? res.description : res.question;
    renderQuestionsListHTML(type, text, originalOrder, false);
}

function restore_Data() {
    try {
        restoreExam();
        questions.iterate(function (value, key, iterationNumber) {
            var id = key.replace(/\D+/gi, '');
            if (key.includes('Intro')) {
                if (value instanceof File || value instanceof Blob) {
                    renderSubjectListHTML(key.split('_')[1].replace(/\d+/gi, ''), value, id, false);
                } else {
                    if (value['data'] && value['data'] != request_names.current_exam + key.replace('_data', '')) {
                        if (route_is_update && (key.indexOf('file') > -1 || key.indexOf('image') > -1 || key.indexOf('audio') > -1)) {
                            axios.get(getFileURL.replace('file_path', value['data']))
                                .then(function (res) {
                                    renderSubjectListHTML(key.split('_')[1].replace(/\d+/gi, ''), res.data, id, false);
                                    sort_list('.list-unstyled.quest_subject', 'Intro_sort');
                                })
                                .catch(function (res) {
                                    console.log(res);
                                    //renderSubjectListHTML(key.split('_')[1].replace(/\d+/gi, ''), value['data'], id, false);
                                });
                        } else {
                            renderSubjectListHTML(key.split('_')[1].replace(/\d+/gi, ''), value['data'], id, false);
                        }
                    }
                }
            } else if (/^MultipleChoiceQuestion\d+$/.test(key) || /^Puzzle\d+$/.test(key) || /^WordGame\d+$/.test(key) || /^Project\d+$/.test(key)) {
                restoreQuestions(key, value, id);
            }

        })
            .then(function () {
                sort_list('.list-unstyled.quest_subject', 'Intro_sort');
                sort_list('.question_lists', 'Questions_sort');
                return;
            })
            .catch(function (err) {
                console.log(err);
            })
    } catch (err) {
        console.log(err);
    }

}

function populateDB(exam, intro, exam_questions, data_copy_with_urls) {
    console.log(exam, intro, exam_questions);

    var promises = [questions.setItem('Exam', exam)];
    var intro_sort = [];
    var qsort = [];
    for (var key in intro) {
        var DBkeyName = key.split('_');
        var index = parseInt(DBkeyName[0]) + 1;
        DBkeyName = 'Intro_' + DBkeyName[1] + index;
        data_copy_with_urls[DBkeyName] = {
            'data': isJSON(data_copy_with_urls['Intro'][key]) || data_copy_with_urls['Intro'][key],
            'o': key.split('_')[0]
        };
        promises.push(questions.setItem(DBkeyName, {'data': isJSON(intro[key]) || intro[key], 'o': key.split('_')[0]}));
        intro_sort.push(DBkeyName);
    }
    //delete data_copy_with_urls['Intro'];
    intro_sort.push(intro_sort.length);

    for (var key in exam_questions) {
        var DBkeyName = key.split('_');
        var index = parseInt(DBkeyName[0]) + 1;
        DBkeyName = DBkeyName[1] + index;
        for (const attr in exam_questions[key]) {
            data_copy_with_urls['questions'][key][attr] = isJSON(data_copy_with_urls['questions'][key][attr]) || data_copy_with_urls['questions'][key][attr];
            exam_questions[key][attr] = isJSON(exam_questions[key][attr]) || exam_questions[key][attr];
        }
        data_copy_with_urls[DBkeyName] = data_copy_with_urls['questions'][key];
        promises.push(questions.setItem(DBkeyName, exam_questions[key]));
        qsort.push(DBkeyName);
    }
    //delete data_copy_with_urls['questions'];
    qsort.push(qsort.length);
    promises.push(questions.setItem('Intro_sort', intro_sort));
    promises.push(questions.setItem('Questions_sort', qsort));
    return Promise.all(promises);
}

function sort_list(list_cls, dbkey) {
    questions.getItem(dbkey)
        .then(function (res) {
            var list = $(list_cls);
            var original_cnt = res.pop();
            for (var i = 0; i < res.length; i++) {
                list.append($("div[data-id='" + res[i] + "']").parent());
            }
            var leftover = original_cnt - res.length;
            console.log('len:' + res.length, 'last:' + original_cnt, 'leftov:' + leftover);
            for (var i = 0; i < leftover; i++) {
                var element = document.createElement('li');
                list.append(element);
            }
        })
        .catch(function (err) {
            console.log(err);
        })
}

function update_order(cls, key) { //TODO: save list count as last element
    var sorting = [];
    $(cls + " .dragBx").each(function () {
        sorting.push($(this).attr('data-id'))
    })
    //console.log(sorting)
    sorting.push(getQuestionsListCount(cls) - 1);
    questions.setItem(key, sorting)
        .then(function () {
            sort_list(cls, key);
        })
        .catch(function (err) {
            console.log(err);
        });
    ;
}

$(function () {
    questions.keys().then(function (keys) {
        if (keys.length > 0 && keys.indexOf('Exam') > -1) {
            questions.getItem('Exam')
                .then(function (saved_exam) {
                    if (typeof route_is_update !== 'undefined') {
                        if (!route_is_update || (route_is_update && exam.id == saved_exam.id)) {
                            swal.fire({
                                title: "Backup found",
                                text: "A backup from your previous unfinished edits was found, would you like to restore it?",
                                icon: "info",
                                showCancelButton: true,
                                cancelButtonText: 'delete <i class="fas fa-thumbs-down"></i>',
                                cancelButtonAriaLabel: 'Thumbs down',
                                confirmButtonColor: '#4181A7',
                                confirmButtonText: 'restore <i class="fas fa-thumbs-up"></i>',
                                confirmButtonAriaLabel: 'Thumbs up, great!',
                                cancelButtonColor: '#66b8d9',
                                showClass: {popup: 'animate__animated animate__fadeIn'},
                                hideClass: {popup: 'animate__animated animate__fadeOut'},
                                reverseButtons: true,
                            }).then(function (res) {
                                if (res.isConfirmed) {
                                    if (route_is_update) {
                                        questions.getItem('data_copy_with_urls')
                                            .then(function (res) {
                                                data_copy_with_urls = res;
                                            })
                                            .then(function () {
                                                restore_Data();
                                            });
                                    } else {
                                        restore_Data();
                                    }
                                } else {
                                    questions.clear().then(function () {
                                        if (route_is_update) {
                                            populateDB(exam, intro, exam_questions, data_copy_with_urls) // global vars declared in (exams/create-update.blade.php) view in views folder
                                                .then(function () {
                                                    questions.setItem('data_copy_with_urls', data_copy_with_urls);
                                                    data_copy_with_urls = '';
                                                    exam_questions = '';
                                                    intro = '';
                                                    exam = '';
                                                })
                                                .then(function () {
                                                    restore_Data();
                                                });
                                        }
                                    });
                                }
                            });
                        } else if (route_is_update) {
                            questions.clear().then(function () {
                                populateDB(exam, intro, exam_questions, data_copy_with_urls) // global vars declared in (exams/create-update.blade.php) view in views folder
                                    .then(function () {
                                        return questions.setItem('data_copy_with_urls', data_copy_with_urls);
                                    })
                                    .then(function () {
                                        data_copy_with_urls = '';
                                        exam_questions = '';
                                        intro = '';
                                        exam = '';
                                        restore_Data();
                                    });
                            });
                        }
                    }
                });

        } else if (typeof route_is_update !== 'undefined' && route_is_update) {
            questions.clear().then(function () {
                populateDB(exam, intro, exam_questions, data_copy_with_urls) // global vars declared in (exams/create-update.blade.php) view in views folder
                    .then(function () {
                        return questions.setItem('data_copy_with_urls', data_copy_with_urls);
                    })
                    .then(function () {
                        data_copy_with_urls = '';
                        exam_questions = '';
                        intro = '';
                        exam = '';
                        restore_Data();
                    });
            });
        }
    });

    /*if(route_is_update) {
        questions.keys().then( function(keys) {
            if (keys.length > 0) {
                questions.getItem('data_copy_with_urls')
                .then(function(res) {
                    data_copy_with_urls = res;
                })
                .then(function() {
                    restore_Data();
                });
            } else {
                populateDB(exam, intro, exam_questions, data_copy_with_urls) // global vars declared in (exams/create-update.blade.php) view in views folder
                .then(function() {
                    questions.setItem('data_copy_with_urls', data_copy_with_urls);
                    data_copy_with_urls = '';
                    exam_questions = '';
                    intro = '';
                    exam = '';
                })
                .then( function() {

                    restore_Data();
                });
            }
        })

    } else {
        restore_Data();
    }*/
})

function saveExamDraft(step) {

    //lcalStorage.removeItem("draft_exam_id");
    var exam_icon = $('#imgInp').prop("files")[0];
    var title = $('.exam_title').val();
    var preq_type = $('.preq_type').val();
    var preq_value = $('.preq_value').val();
    var is_random = $('input[type="checkbox"][name="is_random"]').is(":checked");
    var is_retake = $('input[type="checkbox"][name="is_retake"]').is(":checked");
    var is_chat = $('input[type="checkbox"][name="is_chat"]').is(":checked");
    var time_limit = $('input[type="checkbox"][class="timeLimit"]').is(":checked") == true ? $('.tmBx').val() : -1;
    var is_private = $('input[type="checkbox"][name="is_private"]').is(":checked");
    var is_login_required = $('input[type="checkbox"][name="is_login_required"]').is(":checked");
    var login_fields = $('.infld2.login_fields').val();
    var exam = {
        "title": title,
        "preq": {type: preq_type, value: preq_value},
        "random": is_random,
        "retake": is_retake,
        "chat": is_chat,
        "time_limit": time_limit,
        "private": is_private,
        "login_required": is_login_required,
        "login_fields": login_fields
    }
    showLoader();
    questions.getItem('Exam')
        .then(
            function (res) {
                if (res == null) {
                    return questions.setItem('Exam', exam);
                } else {
                    for (const key in res) {
                        //console.log(exam[key], res[key]);
                        exam[key] = key in exam ? exam[key] : res[key];
                        //console.log(exam[key]);
                    }
                    return questions.setItem('Exam', exam);
                }
            }
        )
        .then(function () {
            localStorage.setItem("draft_exam_id", 'id');
            hideLoader();
        })
}

function saveExamDraftEdit(step) {
    saveExamDraft();
}

function submitReviewPageEdit() {
    submitReviewPage();
}

function generateTable() {
    var row = $('.IntrPopRow').val();
    var colm = $('.IntrPopClm').val();
    var width = (100 / colm);
    var m = 0;
    var table = $('<table></table>').addClass('foo');
    var table2 = $('<table></table>').addClass('foo');
    for (var i = 0; i < row; i++) {
        rows = $('<tr ></tr>');
        rows2 = $('<tr ></tr>');
        for (var j = 0; j < colm; j++) {
            var rowData = $('<td style="width:' + width + '%"><input onkeyup="generateText(this,this.value)" class="tblCl_' + m + '" data="' + m + '" type="text"></td>');
            var rowData2 = $('<td style="width:' + width + '%" class="tblCl_' + m + '"></td>');
            rows.append(rowData);
            rows2.append(rowData2);
            m++;
        }
        table.append(rows);
        table2.append(rows2);
    }
    $('.dynamicTable').html(table);
    $('.dynamicTable2').html(table2);
}

function renderRows() {
    var row = $('.IntrPopRow').val();
    var colm = parseInt($('.IntrPopClm').val());
    var width = (100 / colm);
    var m = $('.dynamicTable table td').length - 1;
    var rowsCount = $('.dynamicTable table tr').length;
    if (row > rowsCount) {
        row = row - rowsCount;
        for (var i = 0; i < row; i++) {
            rows = $('<tr ></tr>');
            rows2 = $('<tr ></tr>');
            for (var j = 0; j < colm; j++) {
                var rowData = $('<td style="width:' + width + '%"><input onkeyup="generateText(this,this.value)" class="tblCl_' + m + '" data="' + m + '" type="text"></td>');
                var rowData2 = $('<td style="width:' + width + '%" class="tblCl_' + m + '"></td>');
                rows.append(rowData);
                rows2.append(rowData2);
                m++;
            }
            $('.dynamicTable table').append(rows);
            $('.dynamicTable2 table').append(rows2);
        }
    } else if (row < rowsCount) {
        $('.dynamicTable table tr').slice(row).remove();
        $('.dynamicTable2 table tr').slice(row).remove();
    }

}

function renderColumns() {
    var row = $('.IntrPopRow').val();
    var colm = $('.IntrPopClm').val();
    var width = (100 / colm);
    var m = $('.dynamicTable table td').length - 1;
    var rows = $('.dynamicTable table tr');
    var rows2 = $('.dynamicTable2 table tr');
    var colsCount = $('.dynamicTable table tr').first().children('td').length;
    if (colm > colsCount) {
        //colm = colm - colsCount;
        rows.each(function (i) {
            var tds = $(this).children('td');
            var tds2 = rows2.eq(i).children('td');
            for (var j = 0; j < colm; j++) {
                tds.eq(j).css('width', width + '%');
                tds2.eq(j).css('width', width + '%');
                if (j > colsCount - 1) {
                    $(this).append('<td style="width:' + width + '%"><input onkeyup="generateText(this,this.value)" class="tblCl_' + m + '" data="' + m + '" type="text"></td>');
                    rows2.eq(i).append('<td style="width:' + width + '%" class="tblCl_' + m + '"></td>');
                }
                m++;
            }
        });
    } else if (colm < colsCount) {
        rows.each(function (i) {
            $(this).children('td').slice(colm).remove();
            rows2.eq(i).children('td').slice(colm).remove();
        });
    }
}

function generateText(obj, val) {
    var class_nm = $(obj).attr("class");
    // console.log("class nm",class_nm);
    // console.log("length",($('.'+class_nm).length));
    $('body .' + class_nm).each(function (m) {
        //if(m==1){
        $(this).text(val);
        //}
    })
    //$('.'+class_nm)[0].text(val);
}

function saveVideoData(cls) {

    var url = $('.intro_video').val();
    localStorage.setItem("intro_video", url);

    if (url == '') {
        //$('.canBtn').click();
        swal.fire("Error", "Please fill the input area", "error");
    } else {
        urls = getId(url);
        $('.quest_video').val(url);
        $(cls + 'vdoTag').html('<iframe src="https://www.youtube.com/embed/' + urls + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
        $(cls + 'VdoBx').slideDown();
        $('.saveDataPopIntro').attr("onclick", "saveDataPopIntro('video')");
        $('.intro_video').val('');
        $('#mask2').click();
        // if video choose make blank other two if not project
        cls != '.pr' && $('.quest_audio').val('');
        cls != '.pr' && $(cls + 'audTag').html('');
        $(cls + 'AudBx').slideUp();

        cls != '.pr' && $('.quest_image').val('');
        cls != '.pr' && $(cls + '_img_list').html('');
        $(cls + 'ImgBx').slideUp();
    }
}

function saveAudData(cls) {


    var audio_name = localStorage.getItem("intro_draft_audio_name");
    var id = getQuestionsListCount('.question_lists') + '_audio_tmp';
    var name = cls == '.pr' ? 'Project' : cls == '.mc' ? 'MultipleChoiceQuestion' : cls == '.wg' ? 'WordGame' : 'Puzzle';
    id = name + id;
    questions.setItem(id, recorded_audio)
        .then(
            function () {
                recorded_audio = null;
                return questions.getItem(id);
            }
        )
        .then(
            function (value) {
                var recordURL = URL.createObjectURL(value);
                $('.quest_audio').val(audio_name);
                var audio = document.createElement('audio');
                audio.controls = true;
                var src = document.createElement('source');
                src.src = recordURL;
                audio.appendChild(src);
                setTimeout(function () {
                    URL.revokeObjectURL(recordURL);
                    $(cls + 'audTag').html(audio);
                }, 1000);

                $(cls + 'AudBx').slideDown();
                $('#mask2').click();
                $('#recordingsList').html('');

                // if audio choose make blank other two
                cls != '.pr' && $('.quest_video').val('');
                cls != '.pr' && $(cls + 'vdoTag').html('');
                $(cls + 'VdoBx').slideUp();

                cls != '.pr' && $('.quest_image').val('');
                cls != '.pr' && $(cls + '_img_list').html('');
                $(cls + 'ImgBx').slideUp();
                localStorage.removeItem("intro_draft_audio_name");

            }
        )

}

function renderSubjectListHTML(type, text, id = null, sort = true) {
    var list = '.list-unstyled.quest_subject';
    var count = id || getQuestionsListCount(list);
    //var data_sort = sort == null ? '' : 'data-sort="'+sort+'"';
    //var divClass = type == 'project' ? 'prjctItm' : 'quest_list_item';
    //var xmtype = type == 'project' ? '' : ' xmtype=' + type;
    var title, src, iconName, htmlBlock;
    var editEvent = '<span class="drgEdt" onclick="editPopIntro(&#039;' + type + '&#039;,\'' + count + '\')">EDIT</span>';

    if (type == 'title') {
        title = 'Title';
        iconName = 'text';
        htmlBlock = '<div class="drgT2 center title_data">' + text + '</div>';
    } else if (type == 'video') {
        title = 'Video';
        iconName = 'video';
        htmlBlock = '<div class="drgvdo center video_data">' +
            '<iframe src="https://www.youtube.com/embed/' + text + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>' +
            '</div>';
    } else if (type == 'audio') {

        src = (text instanceof Blob || text instanceof File) ? URL.createObjectURL(text) : text;
        title = 'Audio';
        iconName = 'audio';
        htmlBlock = '<div class="drgAudio center">' + '<audio controls>' + '<source onload="URL.revokeObjectURL(this.src);' +
            'console.log(\'revoked\')" type="audio/wav">' + '</audio>' + '</div>';
        editEvent = '';

    } else if (type == 'image') {
        src = (text instanceof Blob || text instanceof File) ? URL.createObjectURL(text) : text;
        title = 'Image';
        iconName = 'img';
        htmlBlock = '<div class="drgimg center">' + '<img onload="URL.revokeObjectURL(this.src);console.log(\'revoked\')"/>' + '</div>';
        editEvent = '';
    } else if (type == 'paragraph') {
        title = 'Paragraph';
        iconName = 'note';
        htmlBlock = '<div class="drgtxtp  paragraph_data">' + text + '</div>';
    } else if (type == 'table') {
        title = 'Table';
        iconName = 'table';
        htmlBlock = '<div class="drgvdo center">' + text + '</div>';
    } else if (type == 'file') {
        var file_name = (text instanceof Blob || text instanceof File) ? text.name : text;
        title = 'File';
        iconName = 'attach_white';
        htmlBlock = '<div class="drgT2 center title_data">' + file_name + '</div>';
        editEvent = '';
    } else if (type == 'order') {
        title = 'Order Button';
        iconName = 'order-white';
        htmlBlock = '<div class="drgT2 center title_data">' + text + '</div>';
    }
    var html = ' <li data-post-id="' + count + '">' +
        '<div class="dragBx idnt_' + count + '" data="' + type + '" data-id="Intro_' + type + count + '">' +
        '<div class="drgHdr">' +
        '<div class="drgAction swiper-no-swiping">' +
        '<span class="drgDel" onclick="deleteIntro(&#039;' + type + '&#039;,\'' + count + '\')">DELETE</span>' + editEvent +
        '</div>' +
        '<div class="drgT1">' +
        '<img src="' + rootURL + 'images/' + iconName + '.svg"/>' +
        '<span>' + title + '</span>' +
        '</div>' +
        '<div class="drgAction drgAction2 swiper-no-swiping" onmouseover="this.style.cursor = \'grab\' ">' +
        '<i class="fas fa-arrows-alt handle-btn"></i>' +
        '<span class="drgUpar" >Uparrow</span>' +
        '<span class="drgDwar" >Downarrow</span>' +
        '</div>' +
        '</div>' +
        '<div class="drgWhte">' + htmlBlock + '</div>' +
        '</div></li>';
    $(list).append(html);
    if (type == 'image' || type == 'audio') {
        $(list + ' div[data-id="Intro_' + type + count + '"] .drgWhte').find('img, source').each(function () {
            $(this).attr('src', src);
        });
    }
    $(list).sortable("refresh");
    if (sort) {
        update_order('#sortable', 'Intro_sort')
    }
}

function finishDataPopIntroSave(type, cls, data, render = true, DBid = null, record = 0) {
    showLoader();
    var id = type == 'project_file' ? DBid : DBid ? 'Intro_' + type + DBid : 'Intro_' + type + getQuestionsListCount('.list-unstyled.quest_subject');
    var promise;
    var is_file = false;
    if (data instanceof File || data instanceof Blob) {
        is_file = true;
        promise = questions.setItem(type != 'project_file' ? id + '_data' : id, data)
            .then(function () {
                return type != 'project_file' ? questions.setItem(id, {
                    data: request_names.current_exam + id,
                    o: getQuestionsListCount('.list-unstyled.quest_subject')
                }) : 1;
            });  //done TODO: ADD another db key item with the suffix '_data' with the actual data if it's binary and add one more step for it

    } else {
        promise = questions.setItem(id, {data: data, o: getQuestionsListCount('.list-unstyled.quest_subject')});
    }
    promise.then(
        function () {
            return questions.getItem(is_file ? id + '_data' : id);
        }
    )
        .then(
            function (res) {
                render == true ? renderSubjectListHTML(type, is_file ? res : res['data'], null, true) : false;
                hideLoader();
                $(cls).val('');
                recorded_audio = null;
                $('#mask').trigger("click");
                $('.dynamicTable').html('');
                $('.dynamicTable2').html('');
            }
        )
}

function saveDataPopIntro(type, index = '', render = true, record = 0) {
    var audio_name = localStorage.getItem("intro_draft_audio_name");
    if (type == 'title') {
        var title = $('.intro_title').val();
        // console.log(title);
        var d_exam_id = localStorage.getItem("draft_exam_id");
        // console.log("xm id="+d_exam_id);
        if (d_exam_id == null) {
            swal.fire("Error", "Please fill the First page", "error");
        } else {
            if (title == '') {
                swal.fire("Please fill the input area");
            } else {
                finishDataPopIntroSave(type, '.intro_title', title, render, index);
            }
        }
    } else if (type == 'video') {
        var url = $('.intro_video').val();
        localStorage.setItem("intro_video", url);
        urls = getId(url);
        var d_exam_id = localStorage.getItem("draft_exam_id");
        console.log(d_exam_id);
        if (d_exam_id == null) {
            swal.fire("Error", "Please fill the First page", "error");
        } else {
            if (url == '') {
                $('.canBtn').click();
                //swal.fire("Error","Please fill the input area","error");
            } else {
                finishDataPopIntroSave(type, '.intro_video', urls, render, index);
            }
        }
    } else if (type == 'audio') {
        var d_exam_id = localStorage.getItem("draft_exam_id");
        if (d_exam_id == null) {
            $('.canBtn').click();
            //swal.fire("Error","Please fill the First page","error");
        } else {
            var audio_name = localStorage.getItem("intro_draft_audio_name");
            if (recorded_audio == null) {
                swal.fire("Error", "Please upload or record an audio track", "error");
            } else {
                finishDataPopIntroSave(type, null, recorded_audio, render, index, record);
                localStorage.removeItem("intro_draft_audio_name");
            }
        }
    } else if (type == 'image') {
        var d_exam_id = localStorage.getItem("draft_exam_id");
        if (d_exam_id == null) {
            swal.fire("Error", "Please fill the First page", "error");
        } else {
            finishDataPopIntroSave(type, null, $('.introImg').prop("files")[0], render, index);
        }

    } else if (type == 'paragraph') {
        //var content = $('.intro_paragraph').val();
        var content = paragraph_editor.getData();
        console.log(content);
        $('.intro_paragraph').val('');
        $('#mask').click();
        var d_exam_id = localStorage.getItem("draft_exam_id");
        console.log(d_exam_id);
        if (d_exam_id == null) {
            swal.fire("Error", "Please fill the First page", "error");
        } else {
            if (content == '') {
                swal.fire("Error", "Please fill the input area", "error");
            } else {
                finishDataPopIntroSave(type, null, content, render, index);
            }
        }
    } else if (type == 'table') {
        var table_data = $('.dynamicTable2').html();
        console.log("table data", $('.dynamicTable2').length);

        $('#mask').click();
        var d_exam_id = localStorage.getItem("draft_exam_id");
        console.log(d_exam_id);
        if (d_exam_id == null) {
            swal.fire("Error", "Please fill the First page", "error");
        } else {
            finishDataPopIntroSave(type, null, table_data, render, index);
        }

    } else if (type == 'file') {
        var file = $('.introFile').prop("files")[0];
        var num = Math.pow(1024, 3);
        num = num * 3;
        if (file.size < num) {
            var d_exam_id = localStorage.getItem("draft_exam_id");
            console.log(d_exam_id);
            finishDataPopIntroSave(type, null, file, render, index);
        } else {
            iqwerty.toast.toast('Upload Maximum Size: 3M');
            $('#mask').click();
        }
    } else if (type == 'order') {
        var url = $('.order_url').val();
        var d_exam_id = localStorage.getItem("draft_exam_id");
        if (url != '') {
            finishDataPopIntroSave(type, '.order_url', url, render, index);
        } else {
            $('#mask').click();
        }
    } else if (type == 'project_file') {
        var file = $('.projectFile').prop("files")[0];
        var num = Math.pow(1024, 3);
        num = num * 3;
        if (file.size < num) {
            var d_exam_id = localStorage.getItem("draft_exam_id");
            console.log(d_exam_id);
            finishDataPopIntroSave(type, null, file, false, 'Project' + getQuestionsListCount('.question_lists') + '_file_tmp');
        } else {
            iqwerty.toast.toast('Upload Maximum Size: 3M');
            $('#mask').click();
        }

    } else if (type == 'quest_wg_video' || type == 'quest_mc_video' || type == 'quest_pr_video') {

        var param = '.' + type.split('_')[1];
        if (param != '.pr') {
            tmpChecker('_tmp', false)
                .then(function () {
                    saveVideoData(param);
                });
        } else {
            saveVideoData(param);
        }

    } else if (type == 'quest_wg_audio' || type == 'quest_mc_audio' || type == 'quest_pr_audio') {

        var param = '.' + type.split('_')[1];
        if (param != '.pr') {
            tmpChecker('_tmp', false)
                .then(function () {
                    saveAudData(param);
                });
        } else {
            saveAudData(param);
        }
    } else if (type == 'quest_qo_audio') { // question options audio
        var id = getQuestionsListCount('.question_lists') + '_options_option' + index;
        id = 'MultipleChoiceQuestion' + id;
        options_media[id] = 'audio';
        id = id + '_audio_tmp';
        questions.setItem(id, recorded_audio)
            .then(
                function () {
                    return questions.getItem(id);
                }
            )
            .then(
                function (res) {
                    recorded_audio = null;

                    var audio_name = localStorage.getItem("intro_draft_audio_name");
                    if (audio_name == null) {
                        $('.canBtn').click();
                        //swal.fire("Error","Please fill the input area","error");
                    } else {
                        showLoader();
                        $('.quest_voice_option_' + index).val(audio_name);
                        var audio = document.createElement('audio');
                        audio.controls = true;
                        var src = document.createElement('source');
                        src.onload = function () {
                            URL.revokeObjectURL(this.src);
                        }
                        src.src = URL.createObjectURL(res);
                        audio.appendChild(src);
                        setTimeout(function () {
                            hideLoader();
                            $('.ansAud_' + index).html('');
                            $('.ansAud_' + index).append(audio);

                        }, 1000);

                        $('.ansAud_' + index).fadeIn();
                        $('#mask2').click();
                        $('#recordingsList').html('');
                    }
                }
            )


    } else if (type == 'projects_video') {

        var url = $('.intro_video').val();
        if (url == '') {
            $('.canBtn').trigger('click');
            swal.fire("Error", "Please specify a link", "error");
        } else {
            var urls = getId(url);
            questions.setItem('ProjectSubmit_video', urls)
                .then(function () {
                    $('#mask2').trigger('click');
                    $('.project_data_video').html('<iframe src="https://www.youtube.com/embed/' + urls + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
                    $('.project_data_video').slideDown();
                });

        }
    } else if (type == 'projects_audio') {

        if (recorded_audio == null) {
            $('.canBtn').trigger('click');
            swal.fire("Error", "Please record or upload an audio track", "error");
        } else {
            showLoader();
            questions.setItem('ProjectSubmit_audio', recorded_audio)
                .then(function () {
                    var audio = document.createElement('audio');
                    var src = document.createElement('source');
                    audio.controls = true;
                    src.onload = function () {
                        URL.revokeObjectURL(this.src);
                    }
                    src.src = URL.createObjectURL(recorded_audio);
                    audio.append(src);
                    hideLoader();
                    $('.project_data_audio').slideDown();
                    $('.project_data_audio').append(audio);
                    $('#mask2').trigger('click');
                });
        }
    }
    reset_audio_panel();
}

function reset_audio_panel() {
    $('#hide_on_audio_record').show();
    $('.audioSet.record').hide();
    $('#hide_on_audio_upload').show();
    $('#audio_file_name').text('');
    recordingsList.innerHTML = '';

}

function saveDataPopIntroEdit(type, index = '') {
    saveDataPopIntro(type, index);
}

function editPopIntro(type, id) {
    //localStorage.setItem("edit_dintro_id",id);
    questions.getItem('Intro_' + type + id)
        .then(
            function (res) {
                if (type == 'title') {
                    openIntroPop(type);
                    $('.intro_title').val(res['data']);
                    $('.title_svbtn').attr("onclick", "updateIntro('" + type + "'," + id + ")");

                } else if (type == 'table') {
                    openIntroPop(type);
                    var table = res['data'];
                    $('.dynamicTable2').html(table);
                    $('.IntrPopRow').val($('.dynamicTable2 tr').length);
                    $('.IntrPopClm').val($('.dynamicTable2 tr').first().children('td').length);
                    renderRows();
                    renderColumns();
                    var restored_tds = $('.dynamicTable2 td');
                    var tds = $('.dynamicTable td');
                    //console.log(restored_tds);
                    tds.each(function (i) {
                        //console.log(i, restored_tds[i]);
                        $(this).html('<input onkeyup="generateText(this,this.value)" class="tblCl_' + i + '" data="' + i + '" type="text" value="' + restored_tds[i].textContent + '">');
                    });
                    $('.table_svbtn').attr("onclick", "updateIntro('" + type + "'," + id + ")");
                } else if (type == 'video') {
                    openIntroPop(type);
                    $('.intro_video').val(res['data']);
                    $('.video_svbtn').attr("onclick", "updateIntro('" + type + "'," + id + ")");
                } else if (type == 'paragraph') {
                    openIntroPop(type);

                    setTimeout(() => {
                        paragraph_editor.on("instanceReady", function (event) {
                            paragraph_editor.setData(res['data']);
                        });
                        paragraph_editor.setData(res['data']);
                    }, 1000);
                    $('.intro_paragraph').val(res['data']);
                    $('.paragraph_svbtn').attr("onclick", "updateIntro('" + type + "'," + id + ")");
                } else if (type == 'order') {
                    openIntroPop(type);
                    $('.order_url').val(res['data']);
                    $('.order_svbtn').attr("onclick", "updateIntro('" + type + "'," + id + ")");
                }
            }
        )
}

function editPopIntroEdit(type, id) {
    editPopIntro(type, id);
}

function updateIntro(type, id) {
    saveDataPopIntro(type, id, false);
    var selector = '#sortable .dragBx';
    var attrib_val = 'Intro_' + type + id;
    if (type == "title") {
        var title = $('.intro_title').val();
        $(selector).each(function () {
            if ($(this).attr('data-id') == attrib_val) {
                $(this).find('.title_data').text(title);
                $('.mask').click();
                $('.title_svbtn').attr("onclick", "saveDataPopIntro('title')");
                //saveDataPopIntro('title')
            }
        })
    } else if (type == 'video') {
        var video = $('.intro_video').val();
        if (video == '') {
            $('.canBtn').click();
        } else {
            $(selector).each(function () {
                if ($(this).attr('data-id') == attrib_val) {
                    var urls = getId(video);
                    var ifrmhtml = '<iframe src="https://www.youtube.com/embed/' + urls + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
                    $(this).find('.video_data').html(ifrmhtml);
                    $('.mask').click();
                    $('.video_svbtn').attr("onclick", "saveDataPopIntro('video')");
                }
            });
        }
    } else if (type == 'table') {
        $(selector).each(function () {
            if ($(this).attr('data-id') == attrib_val) {
                var table_data = $('.dynamicTable2').html();
                console.log("table data", $('.dynamicTable2').length);
                $(this).find('.drgvdo.center').html(table_data);
                $('.mask').click();
                $('.table_svbtn').attr("onclick", "saveDataPopIntro('table')");
            }
        });

    } else if (type == 'paragraph') {
        var content = paragraph_editor.getData();
        $(selector).each(function () {
            if ($(this).attr('data-id') == attrib_val) {
                $(this).find('.paragraph_data').html(content);
                $('.mask').click();
                $('.paragraph_svbtn').attr("onclick", "saveDataPopIntro('paragraph')");
            }
        });
    } else if (type == 'order') {
        var url = $('.order_url').val();
        $(selector).each(function () {
            if ($(this).attr('data-id') == attrib_val) {
                $(this).find('.title_data').text(url);
                $('.mask').click();
                $('.order_svbtn').attr("onclick", "saveDataPopIntro('order')");
                $('.order_url').val('');
            }
        })
    }
}

function updateIntroEdit(type, id) {
    updateIntro(type, id);
}

function deleteIntro(type, id) {
    swal.fire({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'No, Cancel it! <i class="fas fa-thumbs-down"></i>',
        cancelButtonAriaLabel: 'Thumbs down',
        confirmButtonColor: '#F232A4',
        confirmButtonText: 'Yes, delete it! <i class="fas fa-thumbs-up"></i>',
        confirmButtonAriaLabel: 'Thumbs up, great!',
        cancelButtonColor: '#511285',
        showClass: {popup: 'animate__animated animate__fadeIn'},
        hideClass: {popup: 'animate__animated animate__fadeOut'},
        reverseButtons: true,
    }).then(function (res) {
        if (res.isConfirmed) {
            //console.log(isConfirm);
            questions.removeItem('Intro_' + type + id)
                .then(function () {
                    return questions.removeItem('Intro_' + type + id + '_data')
                })
                .then(function () {
                    $("div[data-id='Intro_" + type + id + "']").slideUp();
                    $("div[data-id='Intro_" + type + id + "']").remove();
                    update_order('#sortable', 'Intro_sort');
                })
        } else {
            return;
        }
    })

        .catch(function (err) {
            console.log(err);
            swal.fire("Error", "error happened", "success");
        })
}

function deleteIntroEdit(type, id) {
    deleteIntro(type, id);
}


//SECTION: function to give id to model according to number questions yet added, will return the currently edited model id if available
function getQuestionsListCount(list) {
    return editing_model == false ? parseInt($(list + ' li').length) + 1 : editing_model;
}

//SECTION: common function for questions tab
function renderQuestionsListHTML(type, text, id = null, sort = true) {

    var list = '.question_lists';
    var count = id || getQuestionsListCount(list);
    //var data_sort = sort == null ? '' : 'data-sort="'+sort+'"';
    var divClass = type == 'project' ? 'prjctItm' : 'quest_list_item';
    var xmtype = type == 'project' ? '' : ' xmtype=' + type;
    var title, param, iconClass, txtClass, DBkey;

    if (type == 'wg') {
        title = 'Words Game';
        param = 'word_game';
        iconClass = 'wrdgmicon';
        txtClass = 'wgQTitle';
        DBkey = 'WordGame' + count;
    } else if (type == 'dd') {
        title = 'Drag & Drop';
        param = 'dd';
        iconClass = 'ddicon';
        txtClass = 'ddTxt';
        DBkey = 'Puzzle' + count;
    } else if (type == 'mcq') {
        title = 'Multiple Choice';
        param = 'mc';
        iconClass = 'mcicon';
        txtClass = 'mcqTxt';
        DBkey = 'MultipleChoiceQuestion' + count;
    } else if (type == 'project') {
        title = 'Project';
        iconClass = 'prjicon';
        param = 'project';
        txtClass = 'projcTitle';
        DBkey = 'Project' + count;
    }

    var delEvent = 'onclick="deleteQuestionDraft(\'' + DBkey + '\')"'; //var delEvent = type == 'project' ? 'onclick="deleteProject('+DBkey+')"' : 'onclick="deleteQuestionDraft('+DBkey+')"';
    var editEvent = 'onclick="editQuestion(\'' + DBkey + '\',&#039;' + param + '&#039;)"';

    var html = '<li><div class="dragBx ' + divClass + '"' + xmtype + ' data="' + count + '" data-id="' + DBkey + '">' +
        '<div class="drgHdr">' +
        '<div class="drgAction swiper-no-swiping">' +
        '<span class="drgDel" ' + delEvent + '>DELETE</span>' +
        '<span class="drgEdt" ' + editEvent + '>EDIT</span>' +
        '</div>' +
        '<div class="drgT1">' +
        '<span>' + title + '</span>' +
        '</div>' +
        '<div class="drgAction drgAction2 swiper-no-swiping" onmouseover="this.style.cursor = \'grab\' ">' +
        '<i class="fas fa-arrows-alt handle-btn"></i>' +
        '<span class="drgUpar">Uparrow</span>' +
        '<span class="drgDwar">Downarrow</span>' +
        '</div>' +
        '</div>' +
        '<div class="drgWhte2"> <span class="' + iconClass + '">' + title + ' Icon</span>' +
        '<div class="drgT3 ' + txtClass + ' center">' + text + '</div>' +
        '</div>' +

        '</div></li>';
    $(list).append(html);
    $(list).sortable("refresh");
    if (sort) {
        update_order('.question_lists', 'Questions_sort');
    }
}

function resetCommonInput() {
    $('.quest_image').val('');
    $('.quest_audio').val('');
    $('.quest_video').val('');
}


//SECTION: questions save functions

//tmpChecker is for persisting question images on confirming edit: if user attempted to edit a question and changed images then decided to cancel
//the '_tmp' suffix added to the DB items protect against altering the submitted data but when he confirms, the _tmp suffix will be removed and the image will persist instead of the old image
function tmpChecker(id, store) {
    var promises = [];
    console.log(store)
    return questions.keys()
        .then(function (keys) {
            for (var i = 0; i < keys.length; i++) {
                if (keys[i].indexOf(id) != -1 && keys[i].indexOf('_tmp') != -1) {
                    promises.push(processTmp(keys[i], store));
                }
            }
            return Promise.all(promises);
        })
}

function processTmp(key, store) {
    var p;
    if (store) {
        p = questions.getItem(key)
            .then(function (item) {
                var store_key = key.replace('_tmp', '');
                return questions.setItem(store_key, item);
            })
            .then(function () {
                return questions.removeItem(key);
            });
    } else {
        p = questions.removeItem(key);
    }
    return p;
}

//in case of editing an existing exam, the exam is loaded with its media assets from server, 'updateQuestionIfExists' function checks if the exam
//has images,video or audio attributes and preserve them

var options_media = {};

function updateQuestionIfExists(id, attributes) {
    console.log(id);
    var p = questions.getItem(id)
        .then(function (final_question) {
            var question = {};
            var media_delete_promises = []
            if (final_question) {
                question = final_question;
            }
            if (/^Project\d+/.test(id) == false) {
                if (attributes['video'] != null) {
                    for (var i = 0; i < 4; i++) {
                        media_delete_promises.push(questions.removeItem(id + '_image' + i));
                    }
                    media_delete_promises.push(questions.removeItem(id + '_audio'));
                    question['image'] ? question['image'] = null : false;
                    question['audio'] ? question['audio'] = null : false;
                } else if ($('.quest_audio').val() && $('.quest_audio').val() != '') {
                    for (var i = 0; i < 4; i++) {
                        media_delete_promises.push(questions.removeItem(id + '_image' + i));
                    }
                    question['image'] ? question['image'] = null : false;
                } else { //if ($('.quest_image').val() && $('.quest_image').val() != '')
                    media_delete_promises.push(questions.removeItem(id + '_audio'));
                    console.log(true);
                    question['audio'] ? question['audio'] = null : false;
                    console.log(temp_question_media_edits);
                    if (temp_question_media_edits.length > 0) {
                        console.log("attrib:", attributes);
                        console.log("q:", question)
                        if (question['image'] instanceof Array) {
                            for (var i = 0; i < temp_question_media_edits.length; i++) {
                                delete question['image'][temp_question_media_edits[i]];
                            }
                        } else if (question['image'] instanceof String) {
                            question['image'] = null;
                        }
                    }
                }
            }
            for (const key in attributes) {
                if (key != 'options' || key != 'pieces') {
                    question[key] = attributes[key];
                } else {
                    for (const option in attributes[key]) {
                        question[key][option] = attributes[key][option];
                        if (route_is_update) {
                            var option_key = key == 'options' ? id + 'options_option' + attributes[key]['index'] : option;
                            if (options_media[option_key] ||
                                (key == 'options' && question[key]['type'] == 'text' && (question[key]['image'] != null || question[key]['audio'] != null))) {
                                question[key]['image'] = null;
                                key == 'options' ? question[key]['audio'] = null : false;
                            }
                        }
                    }

                    options_media = {};
                }
            }
            console.log(question);
            temp_question_media_edits = [];
            return Promise.all(media_delete_promises)
                .then(function () {
                    return questions.setItem(id, question);
                });
        });
    return p;
}

var temp_question_media_edits = [];

function saveProject(exam, id, render = true) {
    var exam_id = exam || localStorage.getItem("draft_exam_id");
    //exam_id=1;
    if (exam_id == null) {
        swal.fire("Error", "Please fill the First page", "error");
    } else {
        //var project = $('.project_title').val();
        var project = project_editor.getData();
        var video = getId($('.quest_video').val());
        video = video == 'error' ? null : video;
        var count = id || getQuestionsListCount('.question_lists');
        var jsonId = 'Project' + count;

        tmpChecker(jsonId, true)
            .then(function () {
                return updateQuestionIfExists(jsonId, {description: project, video: video, order: count});
            })
            .then(
                function () {
                    render == true ? renderQuestionsListHTML('project', project) : false;
                    closeQuestPop('project');
                    popp1close();
                    //reset all popup value
                    $('.project_title').val('');
                    project_editor.setData("");
                    resetCommonInput();
                    $('.pr_img_list').html('');
                    $('.prImgBx').hide();
                    $('.prvdoTag').html('');
                    $('.prVdoBx').hide();
                    $('.praudTag').html('');
                    $('.prAudBx').hide();
                }
            )
            .catch(function (err) {
                console.log(err);
                swal.fire("Error", "Something went wrong!", "error");
            })

    }
}

function saveMultipleChoiceQuest(exam, id, render = true) {
    var exam_id = exam || localStorage.getItem("draft_exam_id");
    //exam_id=1;
    if (exam_id == null) {
        swal.fire("Error", "Please fill the First page", "error");
    } else {
        var count = id || getQuestionsListCount('.question_lists');
        var question = $('.multiple_question').val();
        var video = getId($('.quest_video').val());
        video = video == 'error' ? null : video;
        var answer = $('input[type="radio"][name="mch_check"]:checked').val();

        var mcqquestionObj = {
            question: question,
            video: video,
            answer: answer,
            options: {},
            order: count
        };
        var c = 0;
        $("input[type='radio'][name*='sml_rdio']:checked")
            .each(
                function () {
                    var type = $(this).val();
                    var i = $(this).attr('name').replace('sml_rdio', '') || 1;
                    var name = 'option' + i;
                    var txtAnsCls = '.qst_ans_' + i;

                    option = $(txtAnsCls).val();
                    if (type == 'text') {
                        option = $(txtAnsCls).val();
                    } else if (type == 'image') {

                        if ($('.quest_image_option_' + i).val() == '' && $(txtAnsCls).val() != '') {
                            option = $(txtAnsCls).val();
                            option_type = 'text';
                        } else {
                            option = '';
                        }
                    } else if (type == 'audio') {
                        option = '';
                    }
                    if ((type == 'text' && option != '') || type != 'text') {
                        mcqquestionObj.options[name] = {
                            index: i,
                            text: option,
                            type: type
                        };
                        c = c + 1;
                    }
                }
            );

        if (question != '' && c >= 2) {

            var jsonId = 'MultipleChoiceQuestion' + count;
            tmpChecker(jsonId, true)
                .then(function () {
                    return updateQuestionIfExists(jsonId, mcqquestionObj);
                })
                .then(
                    function () {
                        render == true ? renderQuestionsListHTML('mcq', question) : false;
                        closeQuestPop('multiple_choice');
                        popp1close();
                        $('.mask').click();
                        //reset all popup value
                        $('.multiple_question').val('');
                        $('.quest_image').val('');
                        $('.quest_audio').val('');
                        $('.quest_video').val('');
                        $('.mc_img_list').html('');
                        $('.mcImgBx').hide();

                        for (var x = 1; x < 5; x++) {
                            $('.quest_image_option_' + x).val('');
                            $('.qst_ans_' + x).val('');

                            //remove html prview
                            $('.ansImgArea_' + x).html('');
                            $('.ansImgArea_' + x).hide();
                            $('.ansAud_' + x).html('');
                            $('.ansAud_' + x).hide();
                        }

                        //question html remove
                        $('.mcvdoTag').html('');
                        $('.mcVdoBx').hide();
                        $('.mcaudTag').html('');
                        $('.mcAudBx').hide();
                        $('.mc_img_list').html('');
                        $('.mcImgBx').hide();
                        console.log('done');
                    }
                )
                .catch(
                    function (err) {
                        console.log(err);
                        swal.fire("Error", "Something went wrong! ", "error");
                    }
                )

        } else {
            swal.fire("Error", "Please enter the question and specify at least 2 choices", "error");
        }

    }
}

function saveWordGameQuest(exam, id, render = true) {
    var video = getId($('.quest_video').val());
    video = video == 'error' ? null : video;
    var question = $('.word_game_title').val();
    //check arabic text and reverese
    var arabic = /[\u0600-\u06FF]/;
    var answer = $('.word_game_answer').val();
    var exam_id = exam || localStorage.getItem("draft_exam_id");
    if (exam_id == null) {
        swal.fire("Error", "Please fill the First page", "error");
    } else if (question == '' || answer == '') {
        swal.fire("Error", "Please fill all the fields", "error");
    } else {
        var count = id || getQuestionsListCount('.question_lists');
        var jsonId = 'WordGame' + count;
        tmpChecker(jsonId, true)
            .then(function () {
                return updateQuestionIfExists(jsonId, {video: video, question: question, answer: answer, order: count});
            })
            .then(
                function () {
                    render == true ? renderQuestionsListHTML('wg', question) : false;
                    //reset all data
                    $('.quest_image').val('');
                    $('.quest_video').val('');
                    $('.quest_audio').val('');
                    $('.word_game_title').val('');
                    $('.word_game_answer').val('');
                    $('.wg_img_list').html('');
                    $('.wgImgBx').hide();
                    $('.wgaudTag').html('');
                    $('.wgAudBx').hide();
                    $('.wgvdoTag').html('');
                    $('.wgVdoBx').hide();
                    $('.mask').click();
                    closeQuestPop('word_game');
                }
            )
            .catch(function (err) {
                console.log(err);
            })

    }
}

function saveDDQuestion(id, render = true) {
    var puzzle_title = $('#puzzle_name').val();
    var count = id || getQuestionsListCount('.question_lists');
    //var puzzle_description = CKEDITOR.instances.puzzle_description.getData();//    $('#puzzle_description').val();
    var puzzle_img = $('#puzzle').prop("files")[0];
    var pieces = puzzle_keys;
    console.log(pieces);
    var puzzle = {
        'question': puzzle_title,
        //'description': puzzle_description,
        'pieces': pieces,
        'order': count
    }
    var jsonId = 'Puzzle' + count;

    $("input[class*='ddTgAnsImg_']").each(
        function (i) {
            if ($(this).prop("files")[0] != undefined) {
                var piece = '_pieces_piece' + i;
                questions.setItem(jsonId + piece + '_image', $(this).prop("files")[0]);
            }
            $(this).attr('type', 'text');
            $(this).attr('type', 'file');
        }
    )
    var setPromises = [updateQuestionIfExists(jsonId, puzzle)];
    puzzle_img != null ? setPromises.push(questions.setItem(jsonId + '_puzzle-image', puzzle_img)) : false;
    Promise.all(setPromises).then(function () {
        render == true ? renderQuestionsListHTML('dd', puzzle_title) : false;
        //CKEDITOR.instances.puzzle_description.setData('');
        //reset the dd form
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
        for (var rm = 1; rm <= 4; rm++) {
            var index = rm - 1;
            deleteChoice(index);
            $('.ddTgAnsTxt_' + rm).val('');
            $('.ddTgAnsImg_' + rm).val('');
            $('.crpTrg' + rm).html('<img src="' + rootURL + 'images/image.svg" />');
            $('.target_' + rm).val('');
            $('.target_' + rm + '_imgdata').val('');
            $('.ddTrgtLi_' + rm).removeClass("added");
        }

        //removeDDQsnImg();
        $('.dd_question').val('');
        $('.quest_image').val('');
        resetDDGame();
        closeQuestPop('dd');
        $('.mask').click();
    })
        .catch(function (err) {
            console.log(err);
        })
}

//SECTION: questions update functions
function updateProject(id) {
    var project = $('.project_title').val();
    var exam_id = localStorage.getItem("draft_exam_id");
    var id2 = id.replace(/\D+/i, '');
    saveProject(exam_id, id2, false);
    $('.prjctItm').each(function (rs) {
        if ($(this).attr("data-id") == id) {
            $(this).find('.projcTitle').text(project);
        }
    })
    tmptype = false;
    editing_model = false;
}

function updateMultipleChoiceQuest(id) {
    var exam_id = localStorage.getItem("draft_exam_id");
    var question = $('.multiple_question').val();
    var id2 = id.replace(/\D+/i, '');
    saveMultipleChoiceQuest(exam_id, id2, false);
    $('.quest_list_item').each(function (rs) {
        if ($(this).attr("data-id") == id) {
            $(this).find('.mcqTxt').text(question);
        }
    })
    tmptype = false;
    editing_model = false;
}

function updateWordGameQuest(id) {
    var exam_id = localStorage.getItem("draft_exam_id");
    var question = $('.word_game_title').val();
    var id2 = id.replace(/\D+/i, '');
    saveWordGameQuest(exam_id, id2, false);
    $('.quest_list_item').each(function (rs) {
        if ($(this).attr("data-id") == id) {
            $(this).find('.wgQTitle').text(question);
        }
    })
    tmptype = false;
    editing_model = false;
}

function updateDDQuestion(id) {
    var puzzle_title = $('#puzzle_name').val();
    var id2 = id.replace(/\D+/i, '');
    saveDDQuestion(id2, false);
    $('.quest_list_item').each(function () {
        if ($(this).attr('data-id') == id) {
            $(this).find(".ddTxt").text(puzzle_title);
        }
    })
    tmptype = false;
    editing_model = false;
}


// DEPRECATED: EDIT-suffix functions: superficial & not needed anymore except for just being there
function saveProjectEdit() {
    var exam_id = localStorage.getItem("draft_exam_id");
    saveProject(exam_id);
}

function saveMultipleChoiceQuestEdit() {
    var exam_id = localStorage.getItem("draft_exam_id");
    saveMultipleChoiceQuest(exam_id);
}

function saveWordGameQuestEdit() {
    var exam_id = localStorage.getItem("draft_exam_id");
    saveWordGameQuest(exam_id);
}

function updateProjectEdit(id) {
    updateProject(id);
}

function updateMultipleChoiceQuestEdit(id) {
    updateMultipleChoiceQuest(id);
}

function updateWordGameQuestEdit(id) {
    updateWordGameQuest(id);
}

function saveDDQuestionEdit(id) {
    saveDDQuestion(id);
}

function updateDDQuestionEdit(id) {
    updateDDQuestion(id)
}

//SECTION: function for deleting main 4 images for questions
function deleteWgImg(obj, id, name) {
    swal.fire({
        title: "Are you sure?",
        text: "Any deletes are final. Even if you didn't save your edits, you'll have to upload the image again",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: 'No, Cancel it! <i class="fas fa-thumbs-down"></i>',
        cancelButtonAriaLabel: 'Thumbs down',
        confirmButtonColor: '#F232A4',
        confirmButtonText: 'Yes, delete it! <i class="fas fa-thumbs-up"></i>',
        confirmButtonAriaLabel: 'Thumbs up, great!',
        cancelButtonColor: '#511285',
        showClass: {popup: 'animate__animated animate__fadeIn'},
        hideClass: {popup: 'animate__animated animate__fadeOut'},
        reverseButtons: true,
    }).then(function (res) {
        if (res.isConfirmed) {
            questions.removeItem(id)
                .then(function () {
                    var images = $('.quest_image').val();
                    var split_img = images.split(',');
                    split_img = $.grep(split_img, function (value) {
                        return value != id;
                    })

                    var updated_image = split_img.join(); //deafault join by comma ,
                    $('.quest_image').val(updated_image);

                    $(obj).parent().fadeOut();
                })
        }
    })
        .catch(function (err) {
            console.log(err);
        });

}

function deleteWgImgEdit(obj, id, name) {
    deleteWgImg(obj, id, name);

}

//SECTION: 2 functions for deleting & editing questions
var tmptype = '';
var editing_model = '';

function editQuestion(id, type) {
    tmptype = type;
    editing_model = id.replace(/\D+/i, '');
    ;
    var title_cls = type == 'project' ? '.project_title' : type == 'word_game' ? '.word_game_title' : type == 'mc' ? '.multiple_question' : '.dd_question';
    var cls_prefix = type == 'project' ? '.pr' : type == 'word_game' ? '.wg' : type == 'mc' ? '.mc' : '.dd';
    var sv_slctr = type == 'project' ? '.pr_svBtn' : type == 'word_game' ? '.wgSvBtn' : type == 'mc' ? '.mcq_svBtn' : '.ddsaveQsn';
    var pop_cls = type == 'project' ? 'project' : type == 'word_game' ? 'word_game' : type == 'mc' ? 'multiple_choice' : 'dd';
    var event = type == 'project' ? "updateProject('" + id + "')" : type == 'word_game' ? "updateWordGameQuest('" + id + "')" : type == 'mc' ? "updateMultipleChoiceQuest('" + id + "')" : "updateDDQuestion('" + id + "')";
    var img_cls = type == 'project' ? 'primg' : type == 'word_game' ? 'wgonly' : type == 'mc' ? 'mcimg' : 'dd';
    questions.getItem(id)
        .then(function (obj) {
            $(title_cls).val(obj.description || obj.question);
            if (type != 'dd') {
                var p = '';
                if (obj.video != '' && obj.video != null) {
                    var vid = obj.video;
                    var ifrmhtml = '<iframe src="https://www.youtube.com/embed/' + vid + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
                    $(cls_prefix + 'vdoTag').html(ifrmhtml);
                    $(cls_prefix + 'VdoBx').show();
                    $('.quest_video').val("https://www.youtube.com/watch?v=" + obj.video);
                    p = Promise.resolve(true);
                } else {
                    function view_audio_for_edit(audio) {
                        var tempObj = audio;
                        var audioTag = document.createElement('audio');
                        audioTag.controls = true;
                        var audioSrc = document.createElement('source');
                        audioSrc.type = "audio/wav";
                        audioSrc.onload = function () {
                            URL.revokeObjectURL(tempObj);
                        }
                        audioSrc.src = tempObj;
                        audioTag.appendChild(audioSrc);
                        $(cls_prefix + 'audTag').append(audioTag);
                        $(cls_prefix + 'AudBx').show();
                        $('.quest_audio').val(id + '_audio');
                        return false;
                    }

                    p = questions.getItem(id + '_audio')
                        .then(function (audio) {
                            if (audio != null) {
                                return view_audio_for_edit(URL.createObjectURL(audio));
                            } else if (obj.audio != null) {
                                return axios.get(getFileURL.replace('file_path', obj.audio))
                                    .then(function (res) {
                                        return view_audio_for_edit(res.data);
                                    })
                                    .catch(function (err) {
                                        console.log(err);
                                    });
                                ;
                            } else {
                                return true;
                            }
                        })
                        .then(function (continue_) {
                            if (continue_) {
                                var promises = [];
                                if (obj['image']) {
                                    for (var i = 0; i < obj['image'].length; i++) {
                                        if (obj['image'][i]) {
                                        promises.push(
                                            axios.get(getFileURL.replace('file_path', obj['image'][i]))
                                                .then(function (res) {
                                                    return res.data;
                                                })
                                        );
                                    }
                                }
                            }
                            var count = promises.length;
                            console.log(count);
                            for (var i = count; i < 4; i++) {

                                promises.push(questions.getItem(id + '_image' + i));
                            }
                            Promise.all(promises)
                                .then(function (res) {
                                    var html = '';
                                    var tmps = [];
                                    for (var i = 0; i < res.length; i++) {

                                        if (res[i] != null) {
                                            var dbid = id + '_image' + i;

                                            var tmpObj = (res[i] instanceof File || res[i] instanceof Blob) ? URL.createObjectURL(res[i]) : res[i];
                                            tmps.push(tmpObj);
                                            $('.quest_image').val($('.quest_image').val() + ',' + dbid);
                                            html += '<li class="wgimg ' + img_cls + '" data="' + dbid + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(\'' + cls_prefix.replace('.', '') + '\',\'' + dbid + '\',\'' + dbid + '\')">X</span>' +
                                                '<div class="wgImgCrop">' +
                                                '<img src="' + tmpObj + '">' +
                                                '</div>' +
                                                '</li>';

                                        } else {
                                            html += ' <li class="tmpImg">' +
                                                '<div class="wgImgCrop" onclick="clickWgQImage(\'' + pop_cls + '\')">' +
                                                '<img src="' + rootURL + 'images/image.svg">' +
                                                '</div>' +
                                                '</li>';
                                        }
                                    }
                                    $(cls_prefix + '_img_list').html(html);
                                    $(cls_prefix + 'ImgBx').show();

                                    setTimeout(function () {
                                        for (var i = 0; i < tmps.length; i++) {
                                            console.log("a");
                                            URL.revokeObjectURL(tmps[i]);
                                        }
                                    }, 2000);
                                    return;
                                })
                                .catch(function (err) {
                                    console.log("f");
                                });
                        }
                    return;
                }
            )
            }
            p.then(function () {
                var type = tmptype;
                console.log(type);
                if (type == 'word_game') {
                    var arabic = /[\u0600-\u06FF]/;
                    if (arabic.test(obj.answer)) {
                        obj.answer = obj.answer.split("").reverse().join("");
                    }
                    $('.word_game_answer').val(obj.answer);
                } else if (type == 'mc') {
                    var options = obj.options;
                    $('input[type="radio"][name="mch_check"][value="' + obj.answer + '"]').prop("checked", true).trigger('change');
                    for (var option in options) {

                        var type = options[option]['type'];
                        var index = options[option]['index'];
                        var txt = options[option]['text'];
                        $("input[type='radio'][name='sml_rdio" + index + "'][value='" + type + "']").prop("checked", true);
                        if (type == 'text') {
                            $('.qst_ans_' + index).val(txt);
                        } else if (type == 'image') {
                            var dbid = id + '_options_option' + index + '_image';

                            function tmp_image(dbid, index, option) {
                                questions.getItem(dbid)
                                    .then(function (image) {
                                        if (image) {
                                            return URL.createObjectURL(image);
                                        }

                                        return axios.get(getFileURL.replace('file_path', options[option]['image']))
                                            .then(function (res) {
                                                return res.data;
                                            })
                                            .catch(function (err) {
                                                console.log(options[option]);
                                                console.log(err);
                                            });
                                    })
                                    .then(function (img_var) {
                                        var tempObj = img_var;
                                        $('.quest_image_option_' + index).val(dbid);
                                        //preview image
                                        var div = document.createElement('div');
                                        div.className = "imgFld";
                                        var img = document.createElement('img');
                                        img.onload = function () {
                                            URL.revokeObjectURL(tempObj);
                                        }
                                        img.src = tempObj;
                                        div.appendChild(img);
                                        $('.ansImgArea_' + index).append(div);
                                        $('.ansImgArea_' + index).show();
                                        $('.ansTxt_' + index).hide();
                                    })
                            }

                            tmp_image(dbid, index, option);

                        } else if (type == 'audio') {
                            var dbid = id + '_options_option' + index + '_audio';

                            function tmp_audio(dbid, index, option) {
                                questions.getItem(dbid)
                                    .then(function (audio) {
                                        if (audio) {
                                            return URL.createObjectURL(audio);
                                        }
                                        return axios.get(getFileURL.replace('file_path', options[option]['audio']))
                                            .then(function (res) {
                                                return res.data;
                                            })
                                            .catch(function (err) {
                                                console.log(err);
                                            });
                                    })
                                    .then(function (audio) {
                                        $('.quest_voice_option_' + index).val(dbid);
                                        var tempObj = audio;
                                        var audioTag = document.createElement('audio');
                                        audioTag.controls = true;
                                        var audioSrc = document.createElement('source');
                                        audioSrc.type = "audio/wav";
                                        audioSrc.onload = function () {
                                            URL.revokeObjectURL(tempObj);
                                        }
                                        audioSrc.src = tempObj;
                                        audioTag.appendChild(audioSrc);
                                        $('.ansAud_' + index).append(audioTag);
                                        $('.ansAud_' + index).show();
                                        $('.ansTxt_' + index).hide();
                                    })
                            }

                            tmp_audio(dbid, index, option);

                        }
                    }
                }
                console.log(event);
                $(sv_slctr).attr("onclick", event);
                openQuestPop(pop_cls);
            })
                .catch(function (err) {
                    console.log(err);
                })

        }
else
    if (type == 'dd') {
        counter = 0;
        active_selector = '';
        $('.ddsaveQsn').attr("onclick", "updateDDQuestion('" + id + "')");
        openQuestPop('dd');
        Promise.join(questions.getItem(id), questions.getItem(id + "_puzzle-image"), function (obj, blob) {
            puzzle_keys = {};
            $('#puzzle_name').val(obj.question);
            $('#puzzle').attr('type', 'text');
            $('#puzzle').attr('type', 'file');
            document.getElementById('keys').textContent = '';
            console.log(obj.pieces);
            document.getElementById('keys').textContent = JSON.stringify(obj.pieces);
            puzzle_keys = obj.pieces;
            start_puzzle_creation();
            layer.removeChildren();
            layerback.removeChildren();
            layerback.add(selectionRectangle);
            ds_ctx.clearRect(0, 0, ds.width, ds.height);
            for (var rm = 1; rm <= 4; rm++) {
                $('.ddTgAnsTxt_' + rm).val('');
                $('.ddTgAnsImg_' + rm).val('');
                $('.crpTrg' + rm).html('<img src="' + rootURL + 'images/image.svg" />');
                $('.target_' + rm).val('');
                $('.target_' + rm + '_imgdata').val('');
                $('.ddTrgtLi_' + rm).removeClass("added");
            }
            addImg(blob);

            setTimeout(function () {
                console.log(obj.pieces)
                var pieces = obj.pieces;
                var os = pieces['piece0']['original_size'];
                var scale = puzzle_canvas.offsetWidth / os['width'];
                for (var piece in pieces) {
                    var current = pieces[piece];
                    var x2 = current['width'] / current['scale'] * scale;
                    var y2 = current['height'] / current['scale'] * scale;
                    var x1 = current['X'] / current['scale'] * scale;
                    var y1 = current['Y'] / current['scale'] * scale;
                    create(x1, y1, x2 + x1, y2 + y1, function (piece, current) {
                        var index = parseInt(piece.replace('piece', ''));

                        if ("hide_origin" in current) {
                            $('#whiteCvr' + index).prop("checked", true);
                            rectCvr(true, piece);
                        }

                        if ("text" in current) {
                            updateTxt(current['text'], piece, 'a_preview');
                            document.getElementById("a_preview_col" + index).style.display = 'unset';
                            document.getElementById("a_col" + index).style.display = 'none';
                        }
                        questions.getItem(id + '_pieces_piece' + index + '_image')
                            .then(function (res) {
                                if (res != null) {
                                    console.log(true);
                                    updateImgfromFile(res, piece, 'a_preview');
                                    document.getElementById("a_preview_col" + index).style.display = 'unset';
                                    document.getElementById("a_col" + index).style.display = 'none';
                                }
                            })
                    }, piece, current)
                }

            }, 2000);
        })
            .catch(function (err) {
                console.log(err);
            })
    }
}

)
.
catch(function (err) {
    console.log(err);
})
}

function deleteQuestionDraft(id) { //NOTE: id is DBkey
    swal.fire({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        showCancelButton: true,
        showCancelButton: true,
        cancelButtonText: 'No, Cancel it! <i class="fas fa-thumbs-down"></i>',
        cancelButtonAriaLabel: 'Thumbs down',
        confirmButtonColor: '#F232A4',
        confirmButtonText: 'Yes, delete it! <i class="fas fa-thumbs-up"></i>',
        confirmButtonAriaLabel: 'Thumbs up, great!',
        cancelButtonColor: '#511285',
        showClass: {popup: 'animate__animated animate__fadeIn'},
        hideClass: {popup: 'animate__animated animate__fadeOut'},
        reverseButtons: true,
    }).then(function (res) {
        if (res.isConfirmed) {
            questions.removeItem(id)
                .then(function () {
                    $("div[data-id='" + id + "']").slideUp();
                    $("div[data-id='" + id + "']").remove();
                    questions.iterate(function (value, key, iterationNumber) {
                        if (key.includes(id)) {
                            questions.removeItem(key);
                            update_order('.question_lists', 'Questions_sort');
                            //HACK: done with each sort and orders are updated at submit time
                        }
                    })
                })
        } else {

        }
    })
        .catch(function (err) {
            console.log(err);
            swal.fire("Error", "Something went wrong!", "error");
        });
}


// DEPRECATED: EDIT-suffix functions: superficial & not needed anymore except for just being there
function editQuestionEdit(id, type) {
    editQuestion(id, type);
}

function deleteQuestionDraftEdit(id) {
    deleteQuestionDraft(id);
}

function editProjectEdit(id) {
    editQuestion(id, 'project');
}

function deleteProjectEdit(id) {
    deleteQuestionDraft(id)
}

//SECTION: function for saving review page
function submitReviewPage() {
    var type = $('.rev_type').val();
    var count = $('.count').val();
    var pass_percentage = $('.pass_percentage').val();
    var rew_mod = $('.rew_mod').val();
    var coupon_list = $('.coupon_list').val();
    var rew_type = $('.rew_type').val();
    var hardware_name = $('.hardware_name').val();
    var charectar = $('.charectar').val();
    var rew_text = $('.rew_text').val();
    var rew_image = $('.rew_image').prop("files")[0];
    var rew_video = $('.rew_video').val();
    var cert_lang = $('.cert_lang').val();
    var cert_logo = $('.cert_logo').prop("files")[0];

    var exam = {
        "review_type": type,
        "question_count": count,
        "pass_percentage": pass_percentage,
        "reward_mode": rew_mod,
        "reward_type": rew_type,
        "coupon_list": coupon_list,
        "hardware_name": hardware_name,
        "special_control_char": charectar,
        "reward_message": rew_text,
        "reward_video": rew_video,
        "cert_lang": cert_lang
    };

    questions.setItem('Exam_reward-image', rew_image)
        .then(function () {
            return questions.setItem('Exam_sponser', cert_logo)
        })
        .then(function () {
            return questions.getItem('Exam')
        })
        .then(
            function (res) {
                if (res == null) {
                    return questions.setItem('Exam', exam);
                } else {
                    for (const key in res) {
                        exam[key] = key in exam ? exam[key] : res[key];
                    }
                    return questions.setItem('Exam', exam);
                }
            }
        )
        .then(fetchAlldata)
        .catch(
            function (err) {
                console.log(err);
            }
        )
}

//SECTION: 2 functions for rendering review page
function renderIntroPreview(v, k) {
    console.log(k);
    var imgName, text;
    var html = '<li>' +
        '<aside class="subTxt">' +
        '<div class="subIcn"><img src="' + rootURL + 'images/#IMG_NAME_HERE#.svg"></div>' +
        '<div class="subTxt2">#TXT_HERE#</div>' +
        '</aside>' +
        '</li>';
    if (k.includes('title')) {
        imgName = 'p_text';
        text = v;
    } else if (k.includes('paragraph')) {
        imgName = 'p_note';
        text = 'paragraph';
    } else if (k.includes('image')) {
        imgName = 'p_img';
        text = 'Image';
    } else if (k.includes('video')) {
        imgName = 'p_video';
        text = 'Video';
    } else if (k.includes('table')) {
        imgName = 'p_table';
        text = 'Table';
    } else if (k.includes('audio')) {
        imgName = 'p_audio';
        text = '';
        var audioBlob = (text instanceof Blob || text instanceof File) ? Promise.resolve(URL.createObjectURL(v)) : axios.get(getFileURL.replace('file_path', v));
        audioBlob
            .then(function (res) {
                var audio = res.data || res;
                getDuration(audio, function (length) {
                    console.log('I got length ' + length);
                    var showTime = '00:00';
                    if (length > 60) {
                        var minute = (length - 60);

                        showTime = parseInt(length / 60) + ':' + ('' + parseInt(length % 60)).padStart(2, '0');
                    } else {
                        if (parseInt(length).toString().length == 1) {
                            showTime = '00:0' + parseInt(length);
                        } else {
                            showTime = '00:' + parseInt(length);
                        }
                    }

                    $('.xm_subjects').html($('.xm_subjects').html().replace(k, showTime.padStart(5, '0')));
                })
            })

        return html.replace('#IMG_NAME_HERE#', imgName).replace('#TXT_HERE#', k);
    } else if (k.includes('file')) {
        imgName = 'attach';
        text = v.name;
    } else if (k.includes('order')) {
        imgName = 'order';
        text = 'Order';
    }
    return html.replace('#IMG_NAME_HERE#', imgName).replace('#TXT_HERE#', text);
}

function fetchAlldata() {

    //1st step: get count of each question type:
    var qTitlesList = $('.question_lists .drgT1');
    $('.xm_total_mcq').text(qTitlesList.find("span:contains('Multiple Choice')").length.toString());
    $('.xm_total_word_game').text(qTitlesList.find("span:contains('Words Game')").length.toString());
    $('.xm_toatl_project').text(qTitlesList.find("span:contains('Project')").length.toString());
    $('.xm_total_dd').text(qTitlesList.find("span:contains('Drag & Drop')").length.toString());

    //2nd get intro data:

    questions.getItem('Intro_sort')
        .then(function (arr) {
            arr.pop();
            var promises = [];
            for (var i = 0; i < arr.length; i++) {
                function temp(key) {
                    var dbkey = (key.indexOf('audio') > -1 || key.indexOf('file') > -1) ? key + '_data' : key;
                    var p = questions.getItem(dbkey)
                        .then(function (value) {
                            var arg = (key.indexOf('audio') > -1 || key.indexOf('file') > -1) ? value : value['data'];
                            return renderIntroPreview(arg, key);
                        });
                    return p;
                }

                promises.push(temp(arr[i]));
            }
            return Promise.all(promises);
        })
        .then(function (res) {
            var intro_html = '';
            for (var i = 0; i < res.length; i++) {
                intro_html += res[i];
            }
            $('.xm_subjects').html(intro_html);
        })

    //3rd get exam data:
    questions.getItem('Exam')
        .then(function (exam) {

            questions.getItem('Exam_icon')
                .then(function (res) {
                    if (res == null) {
                        if (exam['icon'] && exam['icon'] != 'Exam_icon') {
                            axios.get(getFileURL.replace('file_path', exam['icon']))
                                .then(function (res) {
                                    $('.revImg').html('<img src="' + res + '">');
                                });
                        } else {
                            $('.revImg').html('<img src="' + rootURL + 'images/placeholder.jpeg">');
                        }
                    } else {
                        $('.revImg').html('<img src="' + URL.createObjectURL(res) + '" style="max-height: 100%;">');
                    }
                })

            $('.xm_title').text(exam.title);

            var preq_txt = '';
            if (exam.preq.type == 0) {
                preq_txt = 'No prerequisite';
            } else if (exam.preq.type == 1) {
                preq_txt = 'Passing Exam - ' + exam.preq.type;
            } else if (exam.preq.type == 2) {
                preq_txt = 'Group Star - ' + exam.preq.type;
            }
            $('.xm_preq').text(preq_txt);
            $('#rvchk1').prop("checked", exam.random);
            $('#rvchk2').prop("checked", exam.retake);
            $('#rvchk4').prop("checked", exam.chat);
            $('#rvchk5').prop("checked", exam.private);
            if (exam.time_limit != -1) {
                $('#rvchk3').prop("checked", true);
                $('.xm_time').text("Time limit ( " + exam.time_limit + " minutes )");
            }

            var rev_text = '';
            if (exam.review_type == 0) {
                rev_text = '(Default) Do NOT show wrong answer';
            } else if (exam.review_type == 1) {
                rev_text = 'Show only wrong questions at the end of the exam';
            } else if (exam.review_type == 2) {
                rev_text = 'Show wrong questions & Answer ar the end of the exam';
            }
            $('.xm_review').text(rev_text);

            $('.xm_percentage').text(exam.pass_percentage + '%');
            var reward_mode = '';
            if (exam.reward_mode == 0) {
                reward_mode = 'Single';
            } else {
                reward_mode = 'Coupon List';
            }
            $('.xm_reward_mode').val(reward_mode);

            var re_type = '';
            if (exam.reward_type == 0) {
                re_type = 'Bluetooth';
            } else if (exam.reward_type == 1) {
                re_type = 'Show Text Message';
            } else if (exam.reward_type == 2) {
                re_type = 'Show Image';
            } else if (exam.reward_type == 3) {
                re_type = 'Play Video';
            } else if (exam.reward_type == 4) {
                re_type = 'Certificate';
            }
            $('.xm_reward_type').text(re_type);
        })
}

// DEPRECATED: EDIT-suffix functions: superficial & not needed anymore except for just being there
function fetchAlldataEdit() {
    fetchAlldata();
}

function ajaxPromise(url, method, data) {
    var boolattr = method == 'POST' ? false : true;
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: url,
            type: method,
            data: data,
            enctype: 'multipart/form-data',
            contentType: boolattr,
            cache: boolattr,
            processData: boolattr,
            success: function (data) {
                resolve(data)
            },
            error: function (error) {
                reject(error)
            },
        })
    })
}

function idGen() {
    return '_' + Math.random().toString(36).substr(2, 9);
};

function searchExamSuggestion(xmid) {
    console.log(xmid);
    if (xmid == '') {
        $('.hintLst').slideUp();
    } else {
        $.post(base_url + "getExamList", {xmid: xmid}, function (rs) {
            console.log("rs", rs);
            var obj = $.parseJSON(rs);
            if (obj.length == 0) {
                $('.hintLst').slideUp();
            } else {
                var html = '';
                for (i = 0; i < obj.length; i++) {
                    var show_id = parseInt(1000 + parseInt(obj[i].id));
                    html += '<li onclick="putInField(this)" data="' + show_id + '">(' + show_id + ')' + obj[i].examname + '</li>';
                }
                $('.hintLst').html(html);
                $('.hintLst').slideDown();
            }
        })
    }
}

function putInField(obj) {
    var xm_id = $(obj).attr('data');
    $('.preq_value').val(xm_id);
    $('.hintLst').slideUp();
}

function saveExamAsDraft() {
    //just close the page and send to home page
    localStorage.removeItem("draft_exam_id");
    //window.location = base_url+"savingXmDraftFinal";

    swal.fire({
        title: "Saved!",
        type: "success",
        text: "Your exam has been stored as draft"

    }).then(function () {
        localStorage.setItem('draft', true);
        window.location = base_url;
    });
}

var exam_form = new FormData();
var examObj = {};

function publishExam(draft = false) {

    //var intros_order = getIntroItemsOrder();
    //console.log(intros_order);
    //craft the form
    showLoader();
    tmpChecker('_tmp', false) // remove any residual data accidently preserved in case of sudden crashes or interrupts
        .then(function () {
            return Promise.all([questions.getItem('Intro_sort'), questions.getItem('Questions_sort'), questions.keys(), questions.getItem('Exam')]);
        })
        .then(function (promises) {
            var exam = promises[3];
            console.log(promises[2])
            if (route_is_update) {
                if (promises[2].indexOf('sponser') > -1) {
                    exam['sponser'] = null;
                }
                if (promises[2].indexOf('Exam_icon') > -1) {
                    console.log(true);
                    exam['icon'] = null;
                }
                if (promises[2].indexOf('Exam_reward-image') > -1) {
                    exam['reward_image'] = null;
                }
            }
            console.log(exam);
            return Promise.all([questions.setItem('Exam', exam), [promises[0], promises[1], promises[2]]]);
        })
        .then(function (orders_and_keys) {
            orders_and_keys = orders_and_keys[1];
            var keys2 = orders_and_keys[2];
            var intro_sort = orders_and_keys[0] || [];
            var qsort = orders_and_keys[1] || [];
            var keys_to_be_removed = ['Intro_sort', 'Questions_sort', 'data', 'data_copy_with_urls']; //keys used on front end to organize data and not needed on backend
            for (var i = 0; i < keys_to_be_removed.length; i++) {
                var key = keys_to_be_removed[i];
                if (keys2.indexOf(key) > -1) {
                    keys2.splice(keys2.indexOf(key), 1);
                }
            }

            var promises = [];
            for (var element in keys2) {
                var m = keys2[element].split('_')[0].replace(/\d+/gi, '');
                var mArr = [];
                //console.log('key',m);
                var k3 = keys2;
                //console.log(k3);
                for (var child in k3) {
                    var levelone = k3[child].split('_')[0];
                    if (levelone.startsWith(m)) {
                        mArr.push(k3[child]);
                        delete keys2[child];
                        // keys2.splice(child, 1);
                    }
                }
                ;

                //console.log('marr',mArr);

                function add_promise(keysArray, key) {
                    var p = Promise.all(
                        Promise.map(keysArray,
                            function (key) {
                                //console.log('getitem',key);
                                return questions.getItem(key);
                            }
                        )
                    )
                        .then(
                            function (resultsArray) {
                                //console.log('resultarr', resultsArray);
                                //console.log('keysArr',keysArray);
                                var finalMObj = {};
                                for (var y = 0; y < resultsArray.length; y++) {
                                    if (resultsArray[y] instanceof File || resultsArray[y] instanceof Blob || resultsArray[y] == null) { // old condition: keysArray[y].includes('image') || keysArray[y].includes('audio') || keysArray[y].includes('icon') || keysArray[y].includes('sponser')
                                        exam_form.set(request_names.current_exam + keysArray[y], resultsArray[y]);
                                    } else {
                                        var qorder = intro_sort.indexOf(keysArray[y]);//intro
                                        if (qorder != -1) {
                                            resultsArray[y]['o'] = qorder;
                                        } else {
                                            qorder = qsort.indexOf(keysArray[y]);//qs array
                                            if (qorder != -1) {
                                                resultsArray[y]['order'] = qorder;
                                            }
                                        }

                                        var modelkey;
                                        var intro_specific_extra_lvl;
                                        if (keysArray[y].split('_')[1] != undefined) {
                                            modelkey = keysArray[y].split('_')[1].replace(/\d+/gi, '');
                                            intro_specific_extra_lvl = keysArray[y].split('_')[1];
                                            var tempObj = {};
                                            tempObj[intro_specific_extra_lvl] = resultsArray[y];
                                            resultsArray[y] = tempObj;
                                        } else {
                                            modelkey = keysArray[y];
                                        }

                                        if (modelkey in finalMObj) { // NOTE: the cause for this condition structure is to work for questions and intro alike
                                            //and to work with first intro item as well as subsequent into items from the same type
                                            //console.log('true', modelkey);
                                            finalMObj[modelkey][intro_specific_extra_lvl] = resultsArray[y][intro_specific_extra_lvl];
                                        } else {
                                            finalMObj[modelkey] = resultsArray[y];
                                        }
                                    }
                                }

                                examObj[key] = 'Exam' in finalMObj ? finalMObj['Exam'] : finalMObj;//exam_form.set(key, JSON.stringify(finalMObj));
                                //console.log(2);
                                //console.log(finalMObj);
                                return 1;
                            }
                        )
                        .catch(function (err) {
                            console.log(err);
                            //console.log('resultarrerr', resultsArray);
                            //console.log('keysArrerr',keysArray);
                        });
                    return p;
                }

                promises.push(add_promise(mArr, m));
            }
            ;
            //console.log(promises);
            Promise.all(promises)
                .then(function (res) {
                    var ce = request_names.current_exam.replace('_', '');
                    var form_element = {};
                    console.log(examObj);
                    examObj['Exam']['draft'] = draft;
                    form_element[ce] = examObj;
                    exam_form.set('Exams', JSON.stringify(form_element));
                    route_is_update ? exam_form.set('_method', 'put') : false;
                    //console.log(submitURL);
                    axios.post(submitURL, exam_form, {
                        headers: {'content-type': 'multipart/form-data'},
                        onUploadProgress: function (progressEvent) {
                            //TODO: add upload bar
                        }
                    })
                        .then(function (response) {
                            hideLoader();
                            examObj = {};
                            exam_form = new FormData();
                            swal.fire({
                                title: "Saved!",
                                type: "success",
                                text: "Your exam has been published successfully"
                            }).then(function () {
                                questions.clear().then(function () {
                                    window.onbeforeunload = null;
                                    localStorage.setItem('published', true);
                                    window.location = base_url;
                                });
                            });
                        })
                        .catch(function (err) {
                            //console.log(err);
                            hideLoader();
                            swal.fire("Error", "Something went wrong!", "error");
                        });
                }).catch(function (err) {
                console.log(err);
            });
        })
        .catch(function (err) {
            console.log(err);
        });
}

function introPositonUp(id) {
    showLoader();
    $.post(base_url + "updateIntroPosition", {id: id, up: 1}, function (rs) {
        hideLoader();
        if (rs == 1) {
            $("#sortable").load(location.href + " #sortable");
        }
    })
}

function introPositonDown(id) {
    showLoader();
    $.post(base_url + "updateIntroPosition", {id: id, up: 0}, function (rs) {
        hideLoader();
        if (rs == 1) {
            $("#sortable").load(location.href + " #sortable");
        }
    })
}

function introPositonUpQsn(id) {
    showLoader();
    $.post(base_url + "updateIntroPositionQsn", {id: id, up: 1}, function (rs) {
        hideLoader();
        if (rs == 1) {
            $("#sortableQsn").load(location.href + " #sortableQsn");
        }
    })
}

function introPositonDownQsn(id) {
    showLoader();
    $.post(base_url + "updateIntroPositionQsn", {id: id, up: 0}, function (rs) {
        hideLoader();
        if (rs == 1) {
            $("#sortableQsn").load(location.href + " #sortableQsn");
        }
    })
}

function DraftIntroPosition(id, op) {
    showLoader();
    $.post(base_url + "updateDraftIntroPosition", {id: id, op: op}, function (rs) {
        hideLoader();
        if (rs == 1) {
            $("#sortable").load(location.href + " #sortable");
        }
    })

}

function startCountRecording() {
    var sec = 0;
    timer = setInterval(function () {
        sec = parseInt(parseInt(sec) + 1);
        if (sec < 60) {
            if (sec < 10) {
                $('.aud_timer').text('00 : 0' + sec);
            } else {
                $('.aud_timer').text('00 : ' + sec);
            }

        } else if (sec == 60) {
            $('.aud_timer').text('01 : 00');
        } else {
            var min = parseInt(parseInt(sec) / 60);
            secs = (sec - (min * 60));
            var show_min = min;
            if (min < 10) {
                show_min = '0' + min;
            }
            var show_sec = secs;
            if (secs < 10) {
                show_sec = '0' + secs;
            }
            $('.aud_timer').text(show_min + ' : ' + show_sec);
        }
    }, 1000);

}

function markSubmission(passed, url, redirect = false) {
    var req_body = {remark: passed};
    ($('.project_submission_notes') && $('.project_submission_notes').val()) ? req_body['remark_notes'] = $('.project_submission_notes').val() : false;
    axios.put(url, req_body)
        .then(function (res) {
            console.log(res);
            Swal.fire('Success', 'Project Submission Marked as: ' + (passed ? 'Success' : 'Fail'), 'success')
                .then(function () {
                    redirect && window.location.assign($('.base_url').val() + '/profile');
                });
        })
        .catch(function (err) {
            console.log(err);
        })
}

function submitProjectByStudent(submitURL, no_questions, examURL) {

    showLoader();
    var project_text = submission_editor.getData();
    var submission_form = new FormData();
    var promises = [];
    var form_input_names = [];
    submission_form.append('submission_text', project_text);
    if ($('.project_data_img').css('display') != 'none') {
        form_input_names.push('image');
        promises.push(questions.getItem('ProjectSubmit_image'));
    }

    if ($('.project_data_audio').css('display') != 'none') {
        form_input_names.push('audio');
        promises.push(questions.getItem('ProjectSubmit_audio'));
    }

    if ($('.project_data_video').css('display') != 'none') {
        form_input_names.push('video');
        promises.push(questions.getItem('ProjectSubmit_video'));
    }

    Promise.all(promises)
        .then(function (media_assets) {
            for (var i = 0; i < media_assets.length; i++) {
                submission_form.append(form_input_names[i], media_assets[i]);
            }
            return;
        })
        .then(function () {
            return axios.post(submitURL, submission_form, {
                headers: {'content-type': 'multipart/form-data'},
                onUploadProgress: function (progressEvent) {
                    //TODO: add upload bar
                }
            })
        })
        .then(function (response) {
            var swalProps = {
                title: "Success",
                text: "Project submitted successfully",
                icon: "success",
                showCancelButton: true,
                cancelButtonText: '<i class="fas fa-sign-out-alt fa-rotate-180"></i> Close Page',
                cancelButtonAriaLabel: 'close page',
                confirmButtonText: '<i class="fas fa-home"></i> Go to Homepage',
                confirmButtonAriaLabel: 'Homepage',
                confirmButtonColor: '#F232A4',
                showClass: {popup: 'animate__animated animate__fadeIn'},
                hideClass: {popup: 'animate__animated animate__fadeOut'},
                reverseButtons: true,
            }
            if (!no_questions) {
                swalProps['showDenyButton'] = true;
                swalProps['denyButtonText'] = '<i class="fas fa-pencil-alt"></i> Proceed to exam';
                swalProps['denyButtonColor'] = '#511285';
            }
            swal.fire(swalProps).then(function (res) {
                if (res.isConfirmed) {
                    window.location.href = base_url;
                } else if (res.isCanceled) {
                    window.close();
                } else if (res.isDenied) {
                    window.location.href = examURL;
                }
            });
        })
        .catch(function (err) {
            console.log(err);
        });
}

function OpenCroperPop(class_name = '', imgdata) {
    console.log("clikcing here crop open");
    if (class_name == 1) {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {
                //   console.log(event.detail.x);
                //   console.log(event.detail.y);
                //   console.log(event.detail.width);
                //   console.log(event.detail.height);
                //   console.log(event.detail.rotate);
                //   console.log(event.detail.scaleX);
                //   console.log(event.detail.scaleY);
            },
        });
    } else if (class_name == 'quest_icon') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        //$('.cropDImage').attr("onclick","cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'intro_image') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage('intro_image')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'mc_qst_img') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage('mc_qst_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'ansImg_1' || class_name == 'ansImg_2' || class_name == 'ansImg_3' || class_name == 'ansImg_4') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage('" + class_name + "')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'wg_img') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage('wg_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'pr_img') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'project_submit') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage('project_submit')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'cert') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'profile_pic_edit') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        var img = URL.createObjectURL(imgdata);
        $('.cropperJs').on('load', function () {
            URL.revokeObjectURL(this.src);
        })
        $('.cropperJs').attr("src", img);

        $('.cropDImage').attr("onclick", "cropImageEdit('profile_pic_edit')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }

}

function OpenCroperPopEdit(class_name, imgdata) {
    console.log("class_name", class_name);
    // $('.cropperJsPop').show();
    // var shimage = $('.quest_image').val();
    // $('.cropperJs').attr("src","../../api/uploads/"+shimage);

    // $('.cropDImage').attr("onclick","cropImageEdit('"+class_name+"')");

    // const image = document.getElementById('crpImg');
    //  cropper = new Cropper(image, {
    //     initialAspectRatio: 1,
    //     crop(event) {
    //       /*console.log(event.detail.x);
    //       console.log(event.detail.y);
    //       console.log(event.detail.width);
    //       console.log(event.detail.height);
    //       console.log(event.detail.rotate);
    //       console.log(event.detail.scaleX);
    //       console.log(event.detail.scaleY);*/
    //     },
    //   });

    if (class_name == 1) {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {
                //   console.log(event.detail.x);
                //   console.log(event.detail.y);
                //   console.log(event.detail.width);
                //   console.log(event.detail.height);
                //   console.log(event.detail.rotate);
                //   console.log(event.detail.scaleX);
                //   console.log(event.detail.scaleY);
            },
        });
    } else if (class_name == 'quest_icon') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        //$('.cropDImage').attr("onclick","cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'intro_image') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('intro_image')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'mc_qst_img') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('mc_qst_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'ansImg_1' || class_name == 'ansImg_2' || class_name == 'ansImg_3' || class_name == 'ansImg_4') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('" + class_name + "')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'wg_img') {
        console.log("here wg", imgdata);
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('wg_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'pr_img') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'cert') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    } else if (class_name == 'profile_pic_edit') {
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src", imgdata);

        $('.cropDImage').attr("onclick", "cropImageEdit('profile_pic_edit')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2 / 2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }

}

function cropImage(class_name = '', hash) {
    showLoader();
    var img = document.getElementById("crpImg");

    canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
    });

    var crop_image_data = cropper.getData();

    // var exam_id = localStorage.getItem("draft_exam_id");
    canvas.toBlob(function (blob) {
        url = URL.createObjectURL(blob);
        URL.revokeObjectURL(img.src);
        console.log("cropping img", crop_image_data);
        hideLoader();

        if (class_name == '') { //group page
            $('.group_image').val(id);
            $('#group_image_data').val(canvas.toDataURL());
            $('#blah').attr('src', url);
        } else if (class_name == 'intro_image') {

            var d_exam_id = localStorage.getItem("draft_exam_id");
            console.log(d_exam_id);
            if (d_exam_id == null) {
                swal.fire("Error", "Please fill the First page", "error");
            } else {
                var type = 'image';
                finishDataPopIntroSave(type, null, blob, true);
            }
        } else if (class_name == 'project_submit') {
            questions.setItem('ProjectSubmit_image', blob)
                .then(function () {
                    var img = document.createElement('img');
                    img.onload = function () {
                        URL.revokeObjectURL(this.src);
                    }
                    img.src = url;
                    $('.project_data_img').append(img);
                    $('.project_data_img').slideDown();
                });
        } else if (class_name == 'profile_pic_edit') {
            $('#user_image_data').val(canvas.toDataURL());
            $('#blah').attr('src', url);
        } else {
            var id = getQuestionsListCount('.question_lists');
            var name = class_name == 'quest_icon' ? 'Exam_icon' : class_name == 'pr_img' ? 'Project' : (class_name == 'mc_qst_img' || class_name.startsWith('ansImg_')) ? 'MultipleChoiceQuestion' : class_name == 'wg_img' ? 'WordGame' : 'cert';

            var count;
            if (class_name == 'pr_img') {
                count = parseInt($('.pr_img_list').children('.tmpImg').index())
            } else if (class_name == 'mc_qst_img') {
                count = parseInt($('.mc_img_list').children('.tmpImg').index())
            } else if (class_name == 'wg_img') {
                count = parseInt($('.wg_img_list').children('.tmpImg').index())
            } else {
                count = ''
            }
            count = count == -1 ? 0 : count;
            id = (class_name == 'quest_icon' || class_name == 'cert') ? name : class_name.startsWith('ansImg_') == false ? name + id + '_image_tmp' + count : name + id + '_options_option' + class_name.replace('ansImg_', '') + '_image_tmp';
            showLoader();
            questions.setItem(id, blob)
                .then(
                    function () {
                        var imgURL = URL.createObjectURL(blob);
                        if (class_name == 'quest_icon') {
                            $('.imgInp_hidden').val(id);
                            $('#blah').attr('src', imgURL);
                        } else if (class_name == 'cert') {
                            $('.cert_img').attr("src", imgURL);
                            $('.sponsor_img').val(id);
                        } else if (class_name == 'mc_qst_img') {
                            var html = '<li class="wgimg mcimg" data="' + hash + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;mc&#039;,&#039;' + id + '&#039;,&#039;' + hash + '&#039;)">X</span>' +
                                '<div class="wgImgCrop"> ' +
                                '<img src="">' +
                                '</div>' +
                                '</li>';

                            $('.mc_img_list').find('.tmpImg').length == 0 ? $('.mc_img_list').html(html) : $('.mc_img_list').find('.tmpImg').first().replaceWith(html);
                            var existing_real_image = parseInt(parseInt($('.mc_img_list').children('.mcimg').length));

                            //$('.mc_img_list').append();
                            $('.mc_img_list').find('[data="' + hash + '"]').find('img').attr('src', imgURL);
                            var placeHolderImg = '';
                            var num = 4 - existing_real_image - $('.mc_img_list').find('.tmpImg').length;
                            for (var m = 0; m < (num); m++) {
                                placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                    '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">' +
                                    '<img src="' + rootURL + 'images/image.svg">' +
                                    '</div>' +
                                    '</li>';
                                $('.mc_img_list').append(placeHolderImg);
                            }

                            // $('.mc_img_list').append(html);
                            $('.mcImgBx').slideDown();
                            var quest_img = $('.quest_image').val();
                            quest_img = quest_img + ',' + id;
                            $('.quest_image').val(quest_img);
                            // if image choose make blank other two
                            $('.quest_video').val('');
                            $('.mcvdoTag').html('');
                            $('.mcVdoBx').slideUp();
                            $('.quest_audio').val('');
                            $('.mcaudTag').html('');
                            $('.mcAudBx').slideUp();

                        } else if (class_name == 'wg_img') {
                            var html = '<li class="wgimg wgonly" data="' + hash + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;wg&#039;,&#039;' + id + '&#039;,&#039;' + hash + '&#039;)">X</span>' +
                                '<div class="wgImgCrop"> ' +
                                '<img src="">' +
                                '</div>' +
                                '</li>';
                            //

                            $('.wg_img_list').find('.tmpImg').length == 0 ? $('.wg_img_list').html(html) : $('.wg_img_list').find('.tmpImg').first().replaceWith(html);
                            var existing_real_image = parseInt(parseInt($('.wg_img_list').children('.wgonly').length));

                            //$('.wg_img_list').append();
                            $('.wg_img_list').find('[data="' + hash + '"]').find('img').attr('src', imgURL);
                            var placeHolderImg = '';
                            var num = 4 - existing_real_image - $('.wg_img_list').find('.tmpImg').length;
                            for (var m = 0; m < (num); m++) {
                                placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                    '<div class="wgImgCrop" onclick="clickWgQImage()">' +
                                    '<img src="' + rootURL + 'images/image.svg">' +
                                    '</div>' +
                                    '</li>';
                                $('.wg_img_list').append(placeHolderImg);
                            }

                            $('.wgImgBx').slideDown();
                            var quest_img = $('.quest_image').val();
                            quest_img = quest_img + ',' + id;
                            $('.quest_image').val(quest_img);
                            // if image choose make blank other two
                            $('.quest_video').val('');
                            $('.quest_audio').val('');

                            $('#showWgImg').val('');
                            $('.quest_video').val('');
                            $('.wgvdoTag').html('');
                            $('.wgVdoBx').slideUp();
                            $('.quest_audio').val('');
                            $('.wgaudTag').html('');
                            $('.wgAudBx').slideUp();
                        } else if (class_name == 'pr_img') {
                            var html = '<li class="wgimg primg" data="' + hash + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;pr&#039;,&#039;' + id + '&#039;,&#039;' + hash + '&#039;)">X</span>' +
                                '<div class="wgImgCrop"> ' +
                                '<img src="">' +
                                '</div>' +
                                '</li>';

                            $('.pr_img_list').find('.tmpImg').length == 0 ? $('.pr_img_list').html(html) : $('.pr_img_list').find('.tmpImg').first().replaceWith(html);
                            var existing_real_image = parseInt(parseInt($('.pr_img_list').children('.primg').length));
                            //$('.pr_img_list').append();
                            $('.pr_img_list').find('[data="' + hash + '"]').find('img').attr('src', imgURL);
                            var placeHolderImg = '';
                            var num = 4 - existing_real_image - $('.pr_img_list').find('.tmpImg').length;
                            for (var m = 0; m < (num); m++) {
                                placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                    '<div class="wgImgCrop" onclick="clickWgQImage(&#039;project&#039;)">' +
                                    '<img src="' + rootURL + 'images/image.svg">' +
                                    '</div>' +
                                    '</li>';
                                $('.pr_img_list').append(placeHolderImg);
                            }

                            $('.prImgBx').slideDown();

                            var quest_img = $('.quest_image').val();
                            quest_img = quest_img + ',' + id;
                            $('.quest_image').val(quest_img);
                            // if image choose make blank other two
                            $('.quest_video').val('');
                            $('.wgVdoBx').slideUp();
                            $('.quest_audio').val('');
                            $('.wgAudBx').slideUp();

                            $('#showPrImg').val('');
                        } else if (class_name.startsWith('ansImg_')) {
                            options_media[id.replace('_image_tmp', '')] = 'image';

                            var index = parseInt(class_name.replace('ansImg_', ''));
                            console.log($('.ansImgArea_' + index).length);
                            var html = '<div class="imgFld" data="' + hash + '">' +
                                '<img src="">' +
                                '</div>';
                            $('.ansImgArea_' + index).html(html);
                            $('.ansImgArea_' + index).find('[data="' + hash + '"]').find('img').attr('src', imgURL);
                            $('.ansImgArea_' + index).show();
                            $('.ansTxt_' + index).hide();
                            $('.ansAud_' + index).hide();

                            //save image name
                            $('.quest_image_option_' + index).val(id);
                        }
                        URL.revokeObjectURL(img);
                        hideLoader();
                    }
                )
                .catch(function (err) {
                    console.log("Error promise");
                    console.log(err);
                })
        }

        $('.cropperJsPop').hide();
        cropper.destroy();
    });
}

function cropImageEdit(class_name, index) {
    cropImage(class_name, index)
    return;
}

function showHistryReward(url) {
    showLoader();
    axios.get(url)
        .then(function (rs) {
            console.log(rs);
            var reward_type = rs.data.reward_type;
            var reward_data = rs.data.reward_data;

            var span = document.createElement("span");
            if (reward_type == 0) {
                span.innerHTML = "Bluetooth Coupon not supported for web";
            } else if (reward_type == 1) {
                span.innerHTML = reward_data.reward_message;
            } else if (reward_type == 2) {
                span.innerHTML = '<img src="' + reward_data.reward_image + '"/>';
            } else if (reward_type == 3) {
                span.innerHTML = '<iframe src="https://www.youtube.com/embed/' + reward_data.reward_video + '?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
            } else if (reward_type == 4) {
                span.innerHTML = renderCert({
                    lang: reward_data.cert_lang,
                    sponser: reward_data.sponser,
                    name: reward_data.user_name,
                    creation_date: reward_data.creation_date,
                    creation_time: reward_data.creation_time,
                    exam_title: reward_data.exam_title,
                    exam_owner: reward_data.exam_owner,
                    cert_id: reward_data.cert_id
                });
            }
            $('.rewMsk').fadeIn();
            showLoader();
            $(".rewPop .rpBx3T2").html(span);
            setTimeout(function () {
                $('.rewPop').each(function () {
                    $(this).fadeIn();
                })
                hideLoader();
            }, 2000);

        })
        .catch(function (err) {
            console.log(err);
        });
}

function renderCert({lang, sponser, name, creation_date, creation_time, exam_title, exam_owner, cert_id}) {
    var cert_cls = (lang == 'english' || lang == 'en') ? "certificate_en" : (lang == 'arabic' || lang == 'ar') ? "certificate_ab" : '';
    var cert_html = '<div class=" ' + cert_cls + '" onclick="print_cert(' + cert_id + ')">';
    cert_html += `<!--certificate start-->
                                        <div class="srtfVew " id="printFrist" style="background: #fff; padding: 15px;">
                                            <div class="crt1" style="border: 1px solid #511285; padding: 15px;">
                                                <div class="crt2" style="text-align: center; position: relative;">

                                                    <div class="spnrLgo sponsor_img" style="float: left; width: 100%">
                                                    <img src="` + sponser + `"></div>
                                                    <div class="crLne1 " style="margin: 0 auto 20px; width: 25%; float: right">
                                                        <img src="` + base_url + `/images/logo5.svg" style="max-width: 100%;">
                                                    </div>`;

    if (lang == 'en' || lang == 'english') {
        cert_html += `<!--English-->
                                                        <div class="crLne2" >CERTIFICATE</div>
                                                        <div class="crLne3" style="color: #6422A1; font-size: 18px; margin: 0 0 20px 0;">OF ACHIEVEMENT</div>
                                                        <div class="crLne4" style="color: #707070; font-size: 16px;">
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">This is to certify that</div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                <div class="crLne42" style="    display: inline-block; font-style: italic;"><span class="cert_student_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">` + name + `</span></div>
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                has passed an electronic exam on Questanya platform, titled with
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_exam_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">` + exam_title + `</span></div>
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                on<div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_date" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">` + creation_date + `</span></div>@ <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_time" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">` + creation_time + `</span></div>
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                Wishing them further success and excellence
                                                            </div>
                                                        </div>
                                                        <div class="crLne60" style="text-align: left;">
                                                            <div class="crLne6" style="font-size: 12px; text-align: center; min-width: 120px; display: inline-block;">
                                                                <div class="crLne7" style="border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><span class="cert_xm_maker" style="font-weight: bold; padding: 0 2px; font-style: italic; color: #707070" >` + exam_owner + `</span></div>
                                                                <div class="crLne8" style="color:#707070">Exam Maker</div>
                                                            </div>
                                                        </div>`;
    } else if (lang == 'ar' || lang == 'arabic') {
        cert_html += `<!--Arabic-->
        		                		                <div class="crLne2">شهــــــــــــادة</div>
        		                		                <div class="crLne3">إنجـــــــاز</div>
        		                		                <div class="crLne4">
        		                		                	<div class="crLne41">هذه الشهادة تؤكد اجتياز</div>
        		                		                	<div class="crLne41">
        		                		                		<div class="crLne42"><span class="cert_student_name" style="font-weight: bold;">` + name + `</span></div>
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		للاختبار الإلكتروني على منصة كويستانيا والذي بعنوان
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		<div class="crLne42"><span class="cert_exam_name" style="font-weight: bold;">` + exam_title + `</span></div>
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		<div class="crLne42"><span class="cert_xm_date" style="font-weight: bold;">` + creation_date + `</span></div>@ <div class="crLne42"><span class="cert_xm_time" style="font-weight: bold;">` + creation_time + `</span></div> في يوم
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		متمنين لهم دوام النجاح والتميز
        		                		                	</div>
        		                		                </div>

        		                		                <div class="crLne60">
        		                		                	<div class="crLne6">
        		                		                		<div class="crLne7"><span class="cert_xm_maker" style="font-weight: bold; color:#707070">` + exam_owner + `</span></div>
        		                		                		<div class="crLne8" style="color:#707070">صانع الاختبار
        		                		                		</div>
        		                		                	</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    <!--certificate end-->
                                </div>`
    }
    return cert_html;
}

function openComment(url) {
    console.log("clicking");
    showLoader();
    axios.get(url)
        .then(function (rs) {
            console.log(rs);
            hideLoader();

            //show popup
            $('.pcomment').html(rs.data);
            $('.rmMask').fadeIn();
            $('.pcommentBox').fadeIn();
        });
}

function filterHistory(str) {
    var all = $('#set1:checkbox:checked').length;
    console.log("all", all);
    //var fail = $('#set2:checkbox:checked').length;
    var fail = $('#set5:input:checkbox:checked').length;
    console.log("fail", fail);

    var coupon = $('#set2:checkbox:checked').length;
    console.log("coupon", coupon);
    var cert = $('#set3:checkbox:checked').length;
    console.log("cert", cert);
    var other = $('#set4:checkbox:checked').length;
    console.log("other", other);

    if (fail == 0 || coupon == 0 || cert == 0 || other == 0) {
        $('body #set1').prop("checked", false);
    }

    if (all != 0) {
        console.log("here clicking", $('.set2').length);
        $('.hstBx').each(function () {
            $(this).show();
        });
        $('body #set2').prop("checked", true);
        $('body #set3').prop("checked", true);
        $('body #set4').prop("checked", true);
        $('body #set5').prop("checked", true);

    } else if (str == 'all' && all == 0) {
        $('body #set2').prop("checked", false);
        $('body #set3').prop("checked", false);
        $('body #set4').prop("checked", false);
        $('body #set5').prop("checked", false);
    }


    if (fail != 0) {
        $('.hstBx').each(function () {
            if ($(this).attr('status') == '0') {
                $(this).show();
            }
        })
    } else {
        $('.hstBx').each(function () {
            if ($(this).attr('status') == '0') {
                console.log("hiding");
                $(this).hide();
            }
        })
    }

    if (coupon != 0) {
        $('.hstBx').each(function () {
            if ($(this).attr('status') == 'coupon') {
                $(this).show();
            }
        })
    } else {
        $('.hstBx').each(function () {
            if ($(this).attr('status') == 'coupon') {
                $(this).hide();
            }
        })
    }

    if (cert != 0) {
        $('.hstBx').each(function () {
            if ($(this).attr('status') == '4') {
                $(this).show();
            }
        })
    } else {
        $('.hstBx').each(function () {
            if ($(this).attr('status') == '4') {
                $(this).hide();
            }
        })
    }
    if (other != 0) {
        $('.hstBx').each(function () {
            if (($(this).attr('status') != '4') && ($(this).attr('status') != 'coupon') && ($(this).attr('status') != '') && ($(this).attr('status') != '0')) {
                $(this).show();
            }
        })
    } else {
        $('.hstBx').each(function () {
            if (($(this).attr('status') != '4') && ($(this).attr('status') != 'coupon') && ($(this).attr('status') != '') && ($(this).attr('status') != '0')) {
                $(this).hide();
            }
        })
    }
    // if(all!=0){
    // 	$('.hstBx').each(function(){
    // 		$(this).show();
    //     });
    //     $('#set2').click();
    //     $('#set3').click();
    //     $('#set4').click();
    //     $('#set5').click();

    // }else if(fail!=0 && coupon==0 && cert==0 && other==0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='0'){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail==0 && coupon!=0 && cert==0 && other==0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='coupon'){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail==0 && coupon==0 && cert!=0 && other==0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='4'){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail==0 && coupon==0 && cert==0 && other!=0){
    // 	$('.hstBx').each(function(){
    // 		if(($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail!=0 && coupon!=0 && cert==0 && other==0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='0' || $(this).attr('status')=='coupon'){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail!=0 && coupon==0 && cert!=0 && other==0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='0' || $(this).attr('status')=='4'){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail!=0 && coupon==0 && cert==0 && other!=0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='0' && ($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail==0 && coupon!=0 && cert!=0 && other==0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='coupon' || $(this).attr('status')=='4'){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail==0 && coupon!=0 && cert==0 && other!=0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='coupon' && ($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }else if(fail==0 && coupon==0 && cert!=0 && other!=0){
    // 	$('.hstBx').each(function(){
    // 		if($(this).attr('status')=='4' && ($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
    // 			$(this).show();
    // 		}else{
    // 			$(this).hide();
    // 		}
    // 	})
    // }
}

function shareData(title, text, url) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url,
        });
    } else {
        var data = url;
        // console.log("desktop detect");
        var dummy = document.createElement("textarea");
        // to avoid breaking orgain page when copying more words
        // cant copy when adding below this code
        // dummy.style.display = 'none'
        document.body.appendChild(dummy);
        //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
        dummy.value = data;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(low);

        iqwerty.toast.toast('Copied');
    }
}

function qrCodeData(image, title) {
    $('#qr_modal').modal({
        backdrop: false
    });
    $('#qr_modal').find('#qr_modal_modal_body img').attr('src', 'data:image/png;base64,' + image);
    $('#qr_modal').find('#qr_modal_modal_body h3').html(title);
}

function copyGroupInfo(name, id) {
    console.log("called ", name, id);
    var show_id = parseInt(1000 + parseInt(id));
    console.log("show id", show_id);
    show_id = show_id.toString();
    show_id = 'G' + show_id;
    var group_url = base_url + 'group-details/' + id;
    var data = name + ' (' + show_id + ') \n' + group_url;

    var dummy = document.createElement("textarea");
    // to avoid breaking orgain page when copying more words
    // cant copy when adding below this code
    // dummy.style.display = 'none'
    document.body.appendChild(dummy);
    //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
    dummy.value = data;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);

    iqwerty.toast.toast('Copied');
}

function deletGroup(id, id_for_ui) {
    axios.delete(id)
        .then(function (rs) {
            console.log(rs);
            $('.rmv_' + id_for_ui).slideUp();

        })
        .catch(function (err) {
            console.log(err);
            swal.fire("Error", "Something went wrong!", "error");
        })
}

function logout() {
    if (typeof gapi != 'undefined') {
        gapi.auth2.getAuthInstance().signOut().then(function () {
            window.location.href = base_url + "logout";
        });
    } else {
        window.location.href = base_url + "logout";
    }
}

function togdtlclose() {
    $("#togdtl").removeClass("open");
};

function rmExm(id) {
    $('.removeXm_' + id).fadeOut();
}

function followGroup(obj, gid, dicover = '') {
    $.post(base_url + "follow-group", {gid: gid}, function (rs) {
        if (rs == 1) {
            $(obj).text("UNFOLLOW");
            if (dicover == 1) {
                $(obj).removeClass("flwd");
            } else {
                $(obj).removeClass("flw");
            }
            if (dicover == 1) {
                $(obj).attr("onclick", "unfollowGroup(this," + gid + ",1)");
            } else if (dicover == 2) {
                $(obj).attr("onclick", "unfollowGroup(this," + gid + ",2)");
                $(obj).removeAttr("style");
            } else {
                $(obj).attr("onclick", "unfollowGroup(this," + gid + ")");
            }
        }
    })
}

function unfollowGroup(obj, gid, dicover = '') {
    $.post(base_url + "unfollow-group-ajax", {gid: gid}, function (rs) {
        if (rs == 1) {
            $(obj).text("FOLLOW");
            if (dicover == 1) {
                $(obj).addClass("flwd");
            } else {
                $(obj).addClass("flw");
            }
            if (dicover == 1) {
                $(obj).attr("onclick", "followGroup(this," + gid + ",1)");
            } else if (dicover == 2) {
                $(obj).css("background", "#F232A4");
                $(obj).css("color", "#fff");
                $(obj).css("border", "1px solid #F232A4");
                $(obj).attr("onclick", "followGroup(this," + gid + ",2)");

            } else {
                $(obj).attr("onclick", "followGroup(this," + gid + ")");
            }
        }
    });
}

function sendNotificationGroup(url, id) {
    var msg = $('.notification').val();
    var title = $('.notification_title').val();
    var is_news = 0;
    if ($('input[name="nwsBx"]:checked').length > 0) {
        is_news = 1;
    }
    if (msg != '') {
        axios.post(url, {id: id, msg: msg, title: title, is_news: is_news})
            .then(function (rs) {
                console.log("noti", rs);
                $('.notification_title').val('');
                $('.notification').val('');
                $('input[name="nwsBx"]').prop('checked', false)
                closePop();
                $('.pcancel').click();
            })
    } else {
        closePop();
        $('.pcancel').click();
    }
}

function toGrplose() {
    $("#togroup").removeClass("open");
};

$(".nmbrOnly").keydown(function (event) {
    // Allow only backspace and delete
    if (event.keyCode == 46 || event.keyCode == 8) {
        // let it happen, don't do anything
    } else {
        // Ensure that it is a number and stop the keypress
        if (event.keyCode < 48 || event.keyCode > 57) {
            event.preventDefault();
        }
    }
});


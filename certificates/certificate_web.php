<?php if(!isset($_GET['license'])){die();}else{
    include_once "../api/connection.php";
    $licens = base64_decode($_GET['license']);
    $get_license = mysqli_query($conn,"SELECT * FROM onc_licens WHERE id='".mysqli_real_escape_string($conn,$licens)."'");
    $result_license = mysqli_fetch_assoc($get_license);

    $get_user = mysqli_query($conn,"SELECT * FROM onc_insistutions WHERE id='".mysqli_real_escape_string($conn,$result_license['student_Id'])."'");
    $result_user = mysqli_fetch_assoc($get_user);

    $get_exam = mysqli_query($conn,"SELECT * FROM onc_exams WHERE id='".mysqli_real_escape_string($conn,$result_license['courseid'])."'");
    $result_exam = mysqli_fetch_assoc($get_exam);

    $get_maker = mysqli_query($conn,"SELECT * FROM onc_insistutions WHERE id='".mysqli_real_escape_string($conn,$result_exam['teacher'])."'");
    $result_maker = mysqli_fetch_assoc($get_maker);
    
} ?>
<html>
    <head>
        
        <link href="css/cert_style.css" rel="stylesheet" type="text/css" />
        <link href="css/cert_style2.css" rel="stylesheet" type="text/css" />
        <style>
            @page { size: auto;  margin: 0mm; }
            @media print
            {    
                .no-print, .no-print *
                {
                    display: none !important;
                }
            }
            
        </style>
        <style type="text/css" media="print">
            @page { size: landscape; }
            </style>
    </head>
    <body > 
    <?php if($_GET['lang']=='en'){?>
<div class="certificate_en" style="widht:800px">
        <!--certificate start-->
        
        <!--English-->
            <div class="srtfVew " id="printFrist"  style="background: #fff; padding: 15px;">
                <div class="crt1" style="border: 1px solid #511285; padding: 15px;">
                    <div class="crt2" style="text-align: center; position: relative;">
                
                    

                    <div class="spnrLgo sponsor_img" style="position: absolute">
                    <?php if($result_exam['cert_sponsor_img']!='' && $result_exam['cert_sponsor_img']!=null){?>
                        <center><img src="http://ec2-3-133-228-180.us-east-2.compute.amazonaws.com/api/uploads/exam/<?=$result_exam['cert_sponsor_img']?>" style="max-width: 100%; height: 40px;"></center>
                    <?php }?>
                    </div>
                
                    <div class="crLne1 " style="margin: 0 auto 20px; width: 15%; float:right">
                        <img src="images/logo5.svg" style="max-width: 100%;">
                    </div>
                
                <div class="crLne2" style="color: #6422A1; font-size: 60px; font-weight: bold; clear: both">CERTIFICATE</div>
                <div class="crLne3" style="color: #6422A1; font-size: 18px; margin: 0 0 20px 0;">OF ACHIEVEMENT</div>
                <div class="crLne4" style="color: #707070; font-size: 16px;">
                    <div class="crLne41" style="padding: 0 0 12px 0;">This is to certify that</div>
                    <div class="crLne41" style="padding: 0 0 12px 0;">
                        <div class="crLne42" style="    display: inline-block; font-style: italic;"><span class="cert_student_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=$result_user['name']?></span></div>
                    </div>
                    <div class="crLne41" style="padding: 0 0 12px 0;">
                    has passed an electronic exam on Questanya platform, titled with
                    </div>
                    <div class="crLne41" style="padding: 0 0 12px 0;">
                        <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_exam_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=$result_exam['examname']?></span></div>
                    </div>
                    <div class="crLne41" style="padding: 0 0 12px 0;">
                        on<div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_date" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=date('d-m-Y',$result_license['serial'])?></span></div>@ <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_time" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=date('h:i A',$result_license['serial'])?></span></div>
                    </div>
                    <div class="crLne41" style="padding: 0 0 12px 0;">
                        Wishing them further success and excellence
                    </div>
                </div>
                
                <div class="crLne60" style="text-align: left;">
                    <div class="crLne6" style="font-size: 12px; text-align: center; min-width: 120px; display: inline-block;">
                        <div class="crLne7" style="border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><span class="cert_xm_maker" style="font-weight: bold; padding: 0 2px; font-style: italic;"><?=$result_maker['name']?></span></div>
                        <div class="crLne8">Exam Maker</div>
                    </div>
                </div>
                
                    </div>
                </div>
                </div>
    
        <!--certificate end-->
    </div>
    <?php }else{ ?>
        <div class="certificate_ab" style="widht:800px">
        <div class="srtfVew " id="printFrist"  style="background: #fff; padding: 15px;">
            <div class="crt1" style="border: 1px solid #511285; padding: 15px;">
                <div class="crt2" style="text-align: center; position: relative;">
            
                <!--<div class="spnrLgo sponsor_img" style="float: left; max-width: 20%;">
                
            </div>
            
            <div class="crLne1 " style="margin: 0 auto 20px; width: 40%;">
                <img src="images/logo5.svg" style="max-width: 100%;">
            </div>-->
            

            <div class="spnrLgo sponsor_img" style=" position: absolute">
            <?php if($result_exam['cert_sponsor_img']!='' && $result_exam['cert_sponsor_img']!=null){?>
                <center><img src="http://ec2-3-133-228-180.us-east-2.compute.amazonaws.com/api/uploads/exam/<?=$result_exam['cert_sponsor_img']?>" style="max-width: 100%; height: 40px;"></center>
            <?php }?>
            </div>

            <div class="crLne1 " style="margin: 0 auto 20px; width: 15%; float:right">
                <img src="images/logo5.svg" style="max-width: 100%;">
            </div>
            
            <div class="crLne2" style="color: #6422A1; font-size: 60px; font-weight: bold; clear:both">شهــــــــــــادة</div>
            <div class="crLne3" style="color: #6422A1; font-size: 18px; margin: 0 0 20px 0;">إنجـــــــاز</div>
            <div class="crLne4" style="color: #707070; font-size: 16px;">
                <div class="crLne41" style="padding: 0 0 12px 0;">هذه الشهادة تؤكد اجتياز</div>
                <div class="crLne41" style="padding: 0 0 12px 0;">
                    <div class="crLne42" style="    display: inline-block; font-style: italic;"><span class="cert_student_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=$result_user['name']?></span></div>
                </div>
                <div class="crLne41" style="padding: 0 0 12px 0;">
                    للاختبار الإلكتروني على منصة كويستانيا والذي بعنوان
                </div>
                <div class="crLne41" style="padding: 0 0 12px 0;">
                    <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_exam_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=$result_exam['examname']?></span></div>
                </div>
                <div class="crLne41" style="padding: 0 0 12px 0;">
                    <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_date" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=date('d-m-Y',$result_license['serial'])?></span></div>@ <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_time" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?=date('h:i A',$result_license['serial'])?></span></div> في يوم 
                </div>
                <div class="crLne41" style="padding: 0 0 12px 0;">
                    متمنين لهم دوام النجاح والتميز
                </div>
            </div>
            
            <div class="crLne60" style="text-align: left;">
                <div class="crLne6" style="font-size: 12px; text-align: center; min-width: 120px; display: inline-block;">
                    <div class="crLne7" style="border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><span class="cert_xm_maker" style="font-weight: bold; padding: 0 2px; font-style: italic;"><?=$result_maker['name']?></span></div>
                    <div class="crLne8">صانع الاختبار
                    </div>
                </div>
            </div>
            
                </div>
            </div>
            </div>
    </div>
    <?php } ?>
    <center><button class="no-print" onclick="javascript:window.print()">Print</button></center>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            window.print()
        })
        </script>
</html>
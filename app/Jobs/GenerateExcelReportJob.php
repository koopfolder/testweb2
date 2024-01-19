<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Modules\Api\Models\ListMedia;
use DB;
use Excel;
use App\Modules\Api\Http\Controllers\MediaController;
class GenerateExcelReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    /**
     * The maximum number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5; // This number can be whatever you feel is appropriate for your job
    public $maxExceptions = 3;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        $date_now = date('Y-m-d-H-i-s');
        $file_name = 'listmedia-api&webview';
        $type = 'xlsx';
        $items = ListMedia::select(
            'id',
            'title',
            'json_data',
            'template',
            'UploadFileID',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'status',
            'api',
            'web_view',
            'tags',
            'sex',
            'age',
            DB::raw('IF(show_rc=2,"เผยแพร่","ไม่เผยแพร่") as show_rc'),
            DB::raw('IF(show_rc=2,"เผยแพร่","ไม่เผยแพร่") as show_dol'),
            'show_learning',
            'articles_research',
            'research_not_publish_reason',
            'include_statistics',
            'stat_not_publish_reason',
            'knowledges',
            'knowledge_not_publish_reason',
            'media_campaign',
            'campaign_not_publish_reason',
            'interesting_issues',
            'interesting_issues_not_publish_reason',
            'SendMediaTermStatus',
            'media_trash'
        );
        $items->where('show_rc', '=', '2');
        $items->wherenull('media_trash');
        $items->orwhere('show_dol', '!=', '1');
        // $items->wherenull('media_trash');
        // $items->orwhere('status','publish');
        // $items->wherenull('media_trash');
        // $items->orWhere('web_view','=',1);
        // $items->wherenull('media_trash');
        $items->with('createdBy', 'updatedBy');
        $items =  $items->get();

        Excel::create($file_name, function ($excel) use ($items) {
            $excel->sheet('mySheet', function ($sheet) use ($items) {
                //$sheet->fromArray($items,null, 'A1', true);
                $TotalGetMedia = ListMedia::whereDate('created_at', date("Y-m-d"))->count();
                $TotalPersonaMedia = ListMedia::where('show_dol', 2)->count();
                $TotalThrcMedia = ListMedia::where('show_rc', 2)->orwhere('status', "publish")->count();
                $TotalPassMediaTerm = ListMedia::where('SendMediaTermStatus', 50)->count();
                $TotalNotPassMediaTerm = ListMedia::wherenull('SendMediaTermStatus')->count();
                $sheet->row(1, array(
                    'สื่อที่เข้ามาจากDOL' . date('Y-m-d'),
                    $TotalGetMedia . "สื่อ"

                ));
                $sheet->row(2, array(
                    'สื่อที่เผยแพร่บนPersonaHealthทั้งหมด',
                    $TotalPersonaMedia . "สื่อ"

                ));
                $sheet->row(3, array(
                    'สื่อที่เผยแพร่บนResourceCenterทั้งหมด',
                    $TotalThrcMedia . "สื่อ"
                ));
                $sheet->row(4, array(
                    'สื่อวาระกลางทั้งหมด',
                    $TotalPassMediaTerm . "สื่อ"
                ));
                $sheet->row(5, array(
                    'ไม่เป็นสื่อวาระกลางทั้งหมด',
                    $TotalNotPassMediaTerm . "สื่อ"
                ));
                $sheet->row(7, array(
                    // 'id',
                    'ชื่อสื่อ',
                    'ประเภทสื่อ',
                    // 'webview_status',
                    // 'api_status',
                    'สถานะสื่อ',
                    // 'not_publish_reason',
                    // 'format',
                    // 'file_address',
                    // 'json_data',
                    'เพศ',
                    'ช่วงอายุ',
                    'keyword',
                    'ประเด็น',
                    'กลุ่มเป้าหมาย',
                    'กลุ่มพื้นที่',
                    'สำนัก',
                    'สถานะเผยแพร่สื่อบนResourceCenter',
                    'สถานะเผยแพร่สื่อบนPersonaHealth',
                    // 'show_learning',
                    // 'articles_research',
                    // 'research_not_publish_reason',
                    // 'include_statistics',
                    // 'stat_not_publish_reason',
                    // 'knowledges',
                    // 'knowledge_not_publish_reason',
                    // 'media_campaign',
                    // 'campaign_not_publish_reason',
                    // 'interesting_issues',
                    // 'interesting_issues_not_publish_reason',
                    // 'วันที่สร้าง',
                    // 'วันที่แก้ไข',
                    // 'สร้างโดย',
                    // 'updated_by',
                    'SendMediaTermStatus',
                    'ลิงก์สื่อ'
                ));

                $chunkSize = 5000;
                $chunks = $items->chunk($chunkSize);
                $index = 8;

                foreach ($chunks as $chunk) {
                    foreach ($chunk as $value) {

                        // dd($value->knowledges);
                        //dd($key,$value);
                        $json_data = json_decode($value->json_data);

                        $Issues_array = [];
                        if (isset($json_data->Issues)) {
                            foreach ($json_data->Issues as $value_issues) {
                                //dd($value_issues);
                                if (isset($value_issues->Name)) {
                                    array_push($Issues_array, $value_issues->Name);
                                }
                            }
                        }

                        $Targets_array = [];
                        if (isset($json_data->Targets)) {
                            foreach ($json_data->Targets as $value_targets) {
                                //dd($value_issues);
                                if (isset($value_targets->Name)) {
                                    // if(!empty($value_targets->Name)){
                                    array_push($Targets_array, $value_targets->Name);
                                    // }

                                }
                            }
                        }
                        $Settings_array = [];
                        if (isset($json_data->Settings)) {
                            foreach ($json_data->Settings as $value_settings) {
                                //dd($value_issues);
                                if (isset($value_settings->Name)) {
                                    array_push($Settings_array, $value_settings->Name);
                                }
                            }
                        }

                        $sex_text = [];
                        //dd(gettype($value->sex));
                        if ($value->sex != '' && gettype(json_decode($value->sex)) == 'array') {
                            //dd(gettype(json_decode($value->sex)));
                            foreach (json_decode($value->sex) as $value_sex) {
                                //dd($value_issues);
                                $text = '';
                                if ($value_sex == 1) {
                                    $text = 'ชาย';
                                }
                                if ($value_sex == 2) {
                                    $text = 'หญิง';
                                }
                                if ($value_sex == 3) {
                                    $text = 'หลากหลายทางเพศ';
                                }
                                if (!empty($text)) {
                                    array_push($sex_text, $text);
                                }
                            }
                        }
                        $age_text = [];
                        if ($value->age != '' && gettype(json_decode($value->age)) == 'array') {
                            foreach (json_decode($value->age) as $value_age) {
                                //dd($value_issues);
                                $text = '';
                                if ($value_age == 4) {
                                    $text = 'เยาวชน(15–24ปี)';
                                }
                                if ($value_age == 13) {
                                    $text = 'ปฐมวัย(0–5ปี)';
                                }
                                if ($value_age == 19) {
                                    $text = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                                }
                                if ($value_age == 24) {
                                    $text = 'วัยเรียน(6–12ปี)';
                                }
                                if ($value_age == 25) {
                                    $text = 'วัยทำงาน(15-59ปี)';
                                }
                                if ($value_age == 26) {
                                    $text = 'วัยรุ่น(13–15ปี)';
                                }
                                if (!empty($text)) {
                                    array_push($age_text, $text);
                                }
                            }
                        }

                        if ($value->SendMediaTermStatus == '49') {
                            $term = "สื่อวาระกลาง(อยู่ระหว่างพิจารณา)";
                        } elseif ($value->SendMediaTermStatus == '50') {
                            $term = "สื่อวาระกลาง";
                        } else {
                            $term = "ไม่เป็นสื่อวาระกลาง";
                        }
                        $sheet->row($index++, array(
                            // $value->id,
                            $value->title,
                            $value->template,
                            // ($value->web_view == 1 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->api == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            ($value->status == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->status == 'publish' ? '-' : $value->not_publish_reason),
                            // (isset($json_data->Format) ? $json_data->Format : ''),
                            // (isset($json_data->FileAddress) ? $json_data->FileAddress : ''),
                            // $value->json_data,
                            implode(",", $sex_text),
                            implode(",", $age_text),
                            (isset($json_data->Keywords) ? implode(",", $json_data->Keywords) : ''),
                            implode(",", $Issues_array),
                            implode(",", $Targets_array),
                            implode(",", $Settings_array),
                            (isset($json_data->DepartmentName) ? $json_data->DepartmentName : ''),
                            $value->show_rc,
                            $value->show_dol,
                            // $value->show_learning,
                            // ($value->articles_research == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->articles_research == 2 ? '-' : $value->research_not_publish_reason),
                            // ($value->include_statistics == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->include_statistics == 2 ? '-' : $value->stat_not_publish_reason),
                            // ($value->knowledges == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->knowledges == 2 ? '-' : $value->knowledge_not_publish_reason),
                            // ($value->media_campaign == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->media_campaign == 2 ? '-' : $value->campaign_not_publish_reason),
                            // ($value->interesting_issues == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->interesting_issues == 2 ? '-' : $value->interesting_issues_not_publish_reason),
                            // $value->created_at,
                            // $value->updated_at,
                            // $value->created_by,
                            // $value->updated_by,
                            $term,
                            route('media2-detail', base64_encode($value->id))
                        ));
                    }
                }
            });
        })->store('xlsx', storage_path('excel/exports'));
    }

    public function failed($e) //this method execute when job will fail
    {
        DB::table('failed_jobs')->insert([
            'exception' => $e
        ]);
        // Send user notification of failure, etc...
    }
}

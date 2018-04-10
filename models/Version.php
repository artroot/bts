<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "version".
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $finish_date
 * @property int $status
 */
class Version extends \yii\db\ActiveRecord
{
    const RELEASED = 1;
    const UNRELEASED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['start_date', 'finish_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'name' => 'Name',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'finish_date' => 'Release Date',
            'status' => 'Status',
        ];
    }
    
    public function getStatusIcon()
    {
        if ($this->status){
            return '<span class="svg-icon"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">	
 <g>
  <title>Released</title>
  <rect stroke="#636363" id="svg_42" height="12.110095" width="20.834856" y="10.37762" x="1.583136" fill-opacity="0" fill="#a8a8a8"/>
  <rect stroke="#636363" id="svg_43" height="5.553881" width="21.748285" y="4.723721" x="1.126421" fill-opacity="0" fill="#a8a8a8"/>
  <path stroke="#636363" id="svg_49" d="m1.378224,4.582672l2.242179,-2.98979l16.784145,0l2.218355,2.971757" fill-opacity="0" fill="#a8a8a8"/>
  <path id="svg_7" d="m9.562623,15.062425" opacity="0.5" fill-opacity="null" stroke-opacity="null" stroke-width="null" stroke="#636363" fill="none"/>
  <g stroke="null" id="svg_16">
   <ellipse stroke="#636363" ry="4.68619" rx="4.68619" id="svg_3" cy="16.499852" cx="12" stroke-opacity="0" fill="#43b213"/>
   <line stroke="#ffffff" stroke-linecap="null" stroke-linejoin="null" id="svg_14" y2="19.128083" x2="11.656076" y1="16.070859" x1="8.598852" stroke-width="null" fill="none"/>
   <line stroke="#ffffff" stroke-linecap="null" stroke-linejoin="null" id="svg_15" y2="14.694736" x2="15.491796" y1="18.909611" x1="11.276921" stroke-width="null" fill="none"/>
  </g>
 </g>
</svg></span>';
        }else{
            return '<span class="svg-icon"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
 <g>
  <title>Develop</title>
  <rect id="svg_42" height="16.634744" width="20.873471" y="5.746416" x="1.563893" fill-opacity="0" fill="#a8a8a8" stroke="#636363"/>
  <path id="svg_49" d="m1.588968,5.581393l2.197706,-4.114883l16.451259,0l2.174358,4.090064" fill-opacity="0" fill="#a8a8a8" stroke="#636363"/>
  <line fill-opacity="0" stroke-linecap="null" stroke-linejoin="null" id="svg_54" y2="5.599794" x2="3.936003" y1="1.664837" x1="3.936003" fill="#a8a8a8" stroke="#636363"/>
  <line fill-opacity="0" stroke-linecap="null" stroke-linejoin="null" id="svg_55" y2="5.599794" x2="20.108966" y1="1.664837" x1="20.108966" fill="#a8a8a8" stroke="#636363"/>
  <path stroke="#636363" id="svg_2" d="m16.788789,13.597067c-0.024149,-0.240733 -0.070822,-0.474259 -0.129209,-0.703826l-1.364654,-0.188971c-0.118115,-0.284535 -0.274091,-0.548433 -0.457437,-0.790679l0.520188,-1.279356c-0.171048,-0.166814 -0.355772,-0.318901 -0.550197,-0.458843l-1.158769,0.72746c-0.271732,-0.142898 -0.564498,-0.251431 -0.871832,-0.321136l-0.418761,-1.297324c-0.118764,-0.008968 -0.237495,-0.018443 -0.358612,-0.018443s-0.239511,0.009256 -0.358633,0.018443l-0.414612,1.285832c-0.315573,0.067472 -0.614803,0.177265 -0.89352,0.321927l-1.140722,-0.716759c-0.194179,0.139942 -0.378896,0.292029 -0.54993,0.458843l0.505873,1.244018c-0.197165,0.252693 -0.362236,0.531504 -0.486219,0.831995l-1.321574,0.182774c-0.05814,0.229245 -0.105057,0.462523 -0.128958,0.703798l1.186145,0.62757c0.013438,0.330356 0.068209,0.650524 0.165196,0.952023l-0.894763,0.989337c0.104084,0.215884 0.219966,0.425245 0.354022,0.621666l1.317338,-0.282806c0.215987,0.235005 0.462068,0.440907 0.733569,0.611182l-0.049932,1.347832c0.216351,0.097577 0.441142,0.179241 0.672798,0.244517l0.833404,-1.071258c0.147871,0.018878 0.297611,0.031593 0.45034,0.031593c0.165454,0 0.327259,-0.014947 0.486458,-0.03682l0.837744,1.077201c0.232019,-0.0652 0.456468,-0.14686 0.672797,-0.244442l-0.050895,-1.372038c0.258776,-0.167532 0.493427,-0.367961 0.700298,-0.59425l1.351597,0.290014c0.134057,-0.196391 0.249954,-0.405529 0.354001,-0.621594l-0.923744,-1.021728c0.088737,-0.28558 0.138305,-0.587049 0.152736,-0.89802l1.228469,-0.649732zm-3.270277,2.210446l-0.582439,0.379167l-0.358239,-0.550409c-0.186361,0.071683 -0.387,0.11527 -0.598506,0.11527c-0.92374,0 -1.672345,-0.748899 -1.672345,-1.672496c0,-0.923634 0.748605,-1.672244 1.672345,-1.672244c0.923499,0 1.672378,0.74861 1.672378,1.672244c0,0.461549 -0.186973,0.878784 -0.489202,1.181511l0.356008,0.546957z" fill-opacity="0" stroke-width="null" fill="#a8a8a8"/>
 </g>
</svg></span>';
        }
    }
    
}

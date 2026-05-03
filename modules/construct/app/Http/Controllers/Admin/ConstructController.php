<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Artisan;
use Storage;
use Schema;

class ConstructController extends Controller
{
	public function __construct()
	{
	}

	public function createMigration()
    {
        return view('admin.construct.step1');
    }

    public function storeMigration(Request $oRequest)
    {
        $this->validate($oRequest,[
            'table_name' => 'required|alpha_dash|regex:/^[A-Za-z. -_]+$/',
            'field' => 'array|nullable',
            'field.*.name' => 'required|alpha_dash|regex:/^[A-Za-z. -_]+$/'
        ]);
        if(Schema::hasTable($oRequest->input('table_name')))
        {
            return redirect()->back()->withErrors(['table_name'=>'This table already exist'])->withInput($oRequest->all());
        }
        $sClassName = str_replace(' ','',Str::title(str_replace('_',' ',$oRequest->input('table_name'))));
        $aFieldName = ['id'];
        foreach($oRequest->input('field') as $aField)
        {
            $aFieldName[] = $aField['name'];
        }
        if($oRequest->has('table_timestamps'))
        {
            $aFieldName[] = 'created_at';
            $aFieldName[] = 'updated_at';
        }

        /*if(!file_exists(database_path('migrations/'.$oRequest->input('table_name')))){
            mkdir(database_path('migrations/'.$oRequest->input('table_name')), 0755, true);
        }*/

        $sMigration = view('admin.construct.table_migration',['sClassName'=>$sClassName, 'oRequest'=>$oRequest])->render();
        file_put_contents(database_path('migrations/'.date('Y_m_d').'_'.time().'_create_'.strtolower($oRequest->input('table_name')).'_table.php'),$sMigration);

        //Artisan::call('migrate',['--path'=>'/database/migrations/single']);
        session(['table'=>['name'=>strtolower($oRequest->input('table_name')),'fields'=>$aFieldName]]);
        return redirect()->route('admin_construct_create_module_2');
    }

    public function createModel(Request $oRequest)
    {
        $aTableInfo = session('table');
        $aModelFiles = Storage::disk('app')->files('Models');
        $aClasses = [];
        foreach($aModelFiles as $sFileName)
        {
            $aClasses[] = str_replace('Models/','',str_replace('.php','',$sFileName));
        }
        return view('admin.construct.step2',[
            'sModelName'=>$aTableInfo['name'],
            'aFields'=>$aTableInfo['fields'],
            'aClasses'=>$aClasses,
        ]);
    }

    public function storeModel(Request $oRequest)
    {

        $this->validate($oRequest,[
            'model_name' => 'required|alpha_dash|regex:/^[A-Za-z. -]+$/',
            'fillable' => 'array|nullable',
            'hidden' => 'array|nullable',
            //'route_name' => 'nullable',
            'slug' => 'nullable',
            'slug_source' => 'required_with:slug',
            'order' => 'nullable',
            'image' => 'required|in:no-image,only-main,only-addition,full',
            'relation' => 'array|nullable',
            'relation.*.name' => 'required|alpha_dash|regex:/^[A-Za-z. -]+$/',
            'image_proportion' => 'array|nullable',
            'image_proportion.width' => 'nullable|integer|min:1',
            'image_proportion.height' => 'nullable|integer|min:1',
            'addition_sizes' => 'array|nullable'
        ]);
        if($oRequest->has('relation'))
        {
            foreach($oRequest->input('relation') as $iKey=>$aRelationData)
            {
                if(isset($aRelationData['join_table']) && !empty($aRelationData['join_table']))
                {
                    if(Schema::hasTable($aRelationData['join_table']))
                    {
                        return redirect()->back()->withErrors(['relation.'.$iKey.'.join_table'=>'Table "'.$aRelationData['join_table'].'" already exist'])
                            ->withInput($oRequest->all());
                    }
                }
            }
        }
        $aTableInfo = session('table');


        $aRelationInfo = [];
        if(!empty($oRequest->input('relation')))
        {
            $aBelongsToMany = [];
            foreach($oRequest->input('relation') as $aRelationData)
            {
                if($aRelationData['type']=='belongsToMany')
                {


                    $oRelationPathModel = '\App\Models\\'.Str::title($aRelationData['class']);
                    $oRelationModel = new $oRelationPathModel;
                    $sRelationTable = $oRelationModel->getTable();

                    $aRelationData['table_name'] = $sRelationTable;
                    $aBelongsToMany[] = $aRelationData;

                    $sMigrationClassName = str_replace(' ','',Str::title(str_replace('_',' ',$aRelationData['join_table'])));

                    $sJoinMigration = view('admin.construct.join_table_migration',['aRelationship'=>$aRelationData,'sMigrationClassName'=>$sMigrationClassName])->render();
                    file_put_contents(database_path('migrations/'.date('Y_m_d').'_'.time().'_create_'.strtolower($aRelationData['join_table']).'_table.php'),$sJoinMigration);
                }


                $aRelationInfo[] = $aRelationData;
            }

        }
        /*if(isset($aBelongsToMany) && !empty($aBelongsToMany))
        {
            foreach($aBelongsToMany as $iRelKey=>$aRelationship)
            {
                $oRelationPathModel = '\App\Models\\'.title_case($aRelationship['class']);
                $oRelationModel = new $oRelationPathModel;
                $sRelationTable = $oRelationModel->getTable();
                $aBelongsToMany[$iRelKey]['table_name'] = $sRelationTable;

                $sMigrationClassName = str_replace(' ','',title_case(str_replace('_',' ',$aRelationship['join_table'])));

                $sJoinMigration = view('admin.construct.join_table_migration',['aRelationship'=>$aRelationship,'sMigrationClassName'=>$sMigrationClassName])->render();
                file_put_contents(database_path('migrations/'.$aTableInfo['name'].'/'.date('Y_m_d').'_'.time().'_create_'.strtolower($aRelationship['join_table']).'_table.php'),$sJoinMigration);
            }

        }*/
		//Рассчет соотношения сторон исходя из размера изображения ---->
        if($oRequest->input('image','no-image')!='no-image')
        {
            $aSize = $oRequest->input('image_proportion');
            function getDivisorList($px) {
                $dlist = [];
                $i = 1;
                while($px / $i >= 1) {
                    if($px % $i == 0) {
                        $div = $px / $i;
                        $dlist[$div] = $px / $div;
                    }
                    $i++;
                }
                return $dlist;
            }
            $wx = getDivisorList($aSize['width']);
            $hx = getDivisorList($aSize['height']);
            $iAspectWidth = 0;
            $iAspectHeight = 0;

            foreach($wx as $div => $num) {
                if(isset($hx[$div])) {
                    $iAspectWidth = $num;
                    $iAspectHeight = $hx[$div];
                    break;
                }
            }
        }
        else
        {
            $iAspectWidth = 1;
            $iAspectHeight = 1;
        }
        ///<----


        $sModel = view('admin.construct.model',['oRequest'=>$oRequest,'aTableInfo'=>$aTableInfo,'aBelongsToMany'=>isset($aBelongsToMany)?$aBelongsToMany:[]])->render();
        file_put_contents(app_path('Models/'.str_replace('_','',Str::title($oRequest->input('model_name'))).'.php'),$sModel);
        session(['model'=>[
            'name'=>Str::title(str_replace('_','',Str::title($oRequest->input('model_name')))),
            'fields'=>$aTableInfo['fields'],
            'relationships'=>$aRelationInfo,
            'image'=>$oRequest->input('image'),
            'image_proportion'=>['width'=>$iAspectWidth,'height'=>$iAspectHeight],
            'image_size'=>$oRequest->input('image_proportion',['width'=>'','height'=>'']),
            'slug_field'=>$oRequest->input('slug'),
            'slug_source'=>$oRequest->input('slug_source'),
            'order_field'=>$oRequest->input('order'),
            ],
        ]);
        //инфу таблицы можно удалить

        //Artisan::call('migrate',['--path'=>'/database/migrations/'.$aTableInfo['name']]);
        Artisan::call('migrate');
        return redirect()->route('admin_construct_create_module_3');
    }

    public function createController()
    {
        $aModelInfo = session('model');
        $sModelName = $aModelInfo['name'];
        $sPathModel = '\App\Models\\'.$sModelName;//.'()';
        $sModel = new $sPathModel();//'new \App\Models\\'.$sModelName.'()';
        //получить из предидущего шага
        //$aFields = $aModelInfo['fields'];//array_merge($sModel->getFillable(), $sModel->getHidden());
        $aFields = $sModel->getFillable();
        $aRelationships = $aModelInfo['relationships'];
        foreach($aRelationships as $aRelationship)
        {
            if($aRelationship['type']=='belongsTo')
            {
                if(($ikey=array_search($aRelationship['foreign_key'],$aFields))!==false)
                    unset($aFields[$ikey]);
            }
        }
        $aRules = [
            'accepted'=>0,
            'after'=>1,
            'alpha'=>0,
            'alpha_dash'=>0,
            'alpha_num'=>0,
            'array'=>0,
            'before'=>1,
            'between'=>1,
            'boolean'=>0,
            'confirmed'=>0,
            'date'=>0,
            'date_format'=>1,
            'different'=>1,
            'dimensions'=>1,
            'email'=>0,
            'exists'=>1,
            'file'=>0,
            'image'=>0,
            'in'=>1,
            'integer'=>0,
            'ip'=>0,
            'json'=>0,
            'max'=>1,
            'min'=>1,
            'mimes'=>1,
            'nullable'=>0,
            'not_in'=>1,
            'numeric'=>0,
            'required'=>0,
            'string'=>0,
            'size'=>1,
            'unique'=>1,
            'url'=>0,
        ];

        return view('admin.construct.step3',[
            'aAllFields' => $aModelInfo['fields'],
            'aPublicFields' => $aFields,
            'aRelationships' => $aRelationships,
            'sModelName' => $sModelName,
            'aExistIndexFields' => [],
            'aRules' => $aRules,
        ]);
    }

    public function storeController(Request $oRequest)
    {
        $this->validate($oRequest,[
            'controller_name' => 'required|alpha_dash|regex:/^[A-Za-z. -]+$/',
            'search_fields' => 'array|nullable',
            'validation_rules' => 'array|nullable',
            'published_field' => 'string|nullable',
            'index_fields' => 'array|required',
            'show_type' => 'array|required',
            'select_options' => 'array|nullable',
        ]);
        $aModelInfo = session('model');
        //print_r($oRequest->all());
        //echo '<pre>'.print_r($aModelInfo,true).'</pre>';
        $aRelationships = $aModelInfo['relationships'];
        $sModelName = $aModelInfo['name'];
        $sImageType = $aModelInfo['image'];
        $sWithBlock = '';
        if(!empty($aRelationships))
        {
            $sWithBlock.= '->with(';
            $sWithBlock.= '"'.implode('","',array_column($aRelationships,'name')).'"';
            $sWithBlock.= ')';
        }

        //file_put_contents(app_path('Http/Controllers/Admin/'.title_case($oRequest->input('controller_name')).'Controller.php'),$content);

        $sController = view('admin.construct.controller',[
            'aRelationships'=>$aRelationships,
            'sImageType'=>$sImageType,
            'sModelName'=>$sModelName,
            'oRequest'=>$oRequest,
            'sWithBlock'=>$sWithBlock,
            'aModelInfo'=>$aModelInfo
        ])->render();
        file_put_contents(app_path('Http/Controllers/Admin/'.Str::title($oRequest->input('controller_name')).'Controller.php'),$sController);

        $sIndexView = view('admin.construct.index',[
            'sImageType'=>$sImageType,
            'sModelName'=>$sModelName,
            'oRequest'=>$oRequest,
            'aModelInfo'=>$aModelInfo
        ])->render();

        $sFormView = view('admin.construct.form',[
            'sModelName'=>$sModelName,
            'aModelInfo'=>$aModelInfo,
            'aRelationships'=>$aRelationships,
            'sImageType'=>$sImageType,
            'oRequest'=>$oRequest,
        ])->render();


        if(!file_exists(resource_path('views/admin/'.strtolower($oRequest->input('controller_name'))))){
            mkdir(resource_path('views/admin/'.strtolower($oRequest->input('controller_name'))), 0755, true);
        }
        //file_put_contents(resource_path('views/admin/'.strtolower($oRequest->input('controller_name')).'/index.blade.php'),$index_view);
        file_put_contents(resource_path('views/admin/'.strtolower($oRequest->input('controller_name')).'/index.blade.php'),$sIndexView);
        //file_put_contents(resource_path('views/admin/'.strtolower($oRequest->input('controller_name')).'/form.blade.php'),$form_view);
        file_put_contents(resource_path('views/admin/'.strtolower($oRequest->input('controller_name')).'/form.blade.php'),$sFormView);
        $sMenu = '<li class="sidenav-item{{ Route::currentRouteName()=="admin_'.strtolower($sModelName).'_" ? " active" : false }}">
	<a href="{{ route("admin_'.strtolower($sModelName).'_") }}" class="sidenav-link"><i class="sidenav-icon ion ion-md-cog"></i>
		<div>'.Str::title($sModelName).'</div>
	</a>
</li>
';
        file_put_contents(resource_path('views/admin/_menu.blade.php'),$sMenu,FILE_APPEND);

        $sLowerModelName = strtolower($sModelName);
        $sRoutes = "

//Admin ".Str::title($sModelName)."
Route::group(['prefix'=>'$sLowerModelName','as'=>'".$sLowerModelName."_'],function (){
".'$sController'." = \App\Http\Controllers\Admin\\".$oRequest->input('controller_name')."Controller::class;
    Route::get('', [".'$sController'.",'AdminIndex'])->name('');
    Route::get('/create', [".'$sController'.",'AdminCreate'])->name('create');
    Route::get('/edit/{oModel}', [".'$sController'.",'AdminEdit'])->name('edit');
    Route::post('/store', [".'$sController'.",'AdminStore'])->name('store');
    Route::get('/public/{oModel}', [".'$sController'.",'AdminPublic'])->name('public');
    Route::delete('/delete/{oModel}', [".'$sController'.",'AdminDelete'])->name('delete');
});";

        file_put_contents(base_path('routes/admin.php'),$sRoutes,FILE_APPEND);
        return redirect()->route('admin_');
    }

    public function deleteModule()
    {
        return view('admin.construct.delete');
    }

    public function destroyModule()
    {

        if(file_exists(resource_path('views/admin/construct')))
            Storage::disk('resources')->deleteDirectory('views/admin/construct');
        if(file_exists(base_path('modules')))
            Storage::disk('root')->deleteDirectory('modules');
        if(file_exists(base_path('routes/admin.php')))
        {
            $sRouteContent = file_get_contents(base_path('routes/admin.php'));
            $sClearMenuContent = preg_replace('~//Constructor$.*^///Constructor~sm', '', $sRouteContent);
            file_put_contents(base_path('routes/admin.php'),$sClearMenuContent);
        }
        if(file_exists(resource_path('views/admin/_menu.blade.php')))
        {
            $sMenuContent = file_get_contents(resource_path('views/admin/_menu.blade.php'));
            $sClearMenuContent = preg_replace('~\{\{--Constructor--\}\}.*\{\{--/Constructor--\}\}~s', '', $sMenuContent);
            file_put_contents(resource_path('views/admin/_menu.blade.php'),$sClearMenuContent);
        }

        if(file_exists(app_path('Http/Controllers/Admin/ConstructController.php')))
            unlink(app_path('Http/Controllers/Admin/ConstructController.php'));

        return redirect()->route('admin_');
    }

    public function modulesList()
    {
        $aModulesFiles = Storage::disk('root')->directories('modules');
        $aModules = [];
        foreach($aModulesFiles as $sModuleName)
        {
            $aModules[] = str_replace('modules/','',$sModuleName);
        }
        return view('admin.modules.list',['aModules'=>$aModules]);
    }

    public function modulesInstall(Request $oRequest)
    {
        $this->validate($oRequest, [
            'module_name'		=> 'required|array',
        ]);
        $aTables = [];
        foreach($oRequest->input('module_name') as $sModule)
        {
            $aModuleFiles = Storage::disk('root')->allFiles('modules/'.$sModule);
            foreach($aModuleFiles as $sFile)
            {
                if(strpos($sFile,'/database/migrations')!==false)
                {
                    $sMigrationContent = file_get_contents(base_path($sFile));
                    $iStartPosition = strpos($sMigrationContent,'Schema::create(');
                    $iEndPosition = strpos($sMigrationContent,',',$iStartPosition);
                    $iLenght = $iEndPosition-($iStartPosition+strlen('Schema::create(')+1);
                    $sTableName = substr($sMigrationContent,$iStartPosition+strlen('Schema::create(')+1,$iLenght-1);
                    if(Schema::hasTable($sTableName))
                    {
                        //$aTables[$sModule][]=$sTableName;
                        Schema::drop($sTableName);
                    }
                }
            }


            foreach($aModuleFiles as $sFile)
            {
                if(strpos($sFile,'/database/migrations')!==false)
                {
                    $sNewFile = str_replace('/database/migrations','/database/migrations/'.$sModule,$sFile);
                    Storage::disk('root')->copy($sFile,str_replace('modules/'.$sModule.'/','',$sNewFile));
                }
                elseif((strpos($sFile,'/resources/views/admin/_menu.blade.php')!==false))
                {
                    $sMenuContent = file_get_contents(base_path($sFile));
                    file_put_contents(base_path(str_replace('modules/'.$sModule.'/','',$sFile)),$sMenuContent,FILE_APPEND);
                }
                elseif(strpos($sFile,'/routes/web.php')!==false||strpos($sFile,'/routes/admin.php')!==false)
                {
                    $sRouteContent = file_get_contents(base_path($sFile));
                    $sClearRouteContent = mb_substr($sRouteContent,5,NULL,"UTF-8");
                    file_put_contents(base_path(str_replace('modules/'.$sModule.'/','',$sFile)),$sClearRouteContent,FILE_APPEND);
                }
                else
                {
                    Storage::disk('root')->copy($sFile,str_replace('modules/'.$sModule.'/','',$sFile));
                }
            }
            if(!file_exists(public_path('uploads')) && Storage::disk('root')->exists('modules/'.$sModule.'/public/uploads')){
                mkdir(public_path('uploads/'), 0755, true);
            }
            //if(!isset($aTables[$sModule]) || empty($aTables[$sModule]))
            //Artisan::call('migrate:refresh',['--path'=>'/database/migrations/'.$sModule]);

        }
        Artisan::call('migrate',['--path'=>'/database/migrations/**']);
        return redirect()->route('admin_modules_');//->with('migration_error',$aTables);
    }
}

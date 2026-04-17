<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Visit;
use Carbon\Carbon;

class IndexController extends Controller
{
	
	
    public function execute () {
        //dd($this->newsFromBlog());
		
$todayCount = Visit::where('visit_date', Carbon::today())->count();
$allCount   = Visit::count();

        return view('index', [
            //dd($this->featuredProducts()[1][0])
            'massivTovars'=> $this->featuredProducts()[1],
            /*
            ->name
            'countPhotoInFile'=> $this->countPhotoInFile(),
                        'massivCategories'=> $this->massivCategories(),
                        'zagalovokViewTovars'=> $this->featuredProducts()[0],

                        'massivIdTovar'=> $this->featuredProducts()[2],

                        'massivNavigator'=> $this->massivNavigator(),
                        'massivArrayNavigator'=>array_keys($this->massivNavigator()),

                        'massivBlogsPrev' => $this->newsFromBlog()[0],
                        'dateBlogsPrev' => $this->newsFromBlog()[1],
                        'restPrev' => $this->newsFromBlog()[2],
                        'massivBlogsActual' => $this->newsFromBlog()[3],
                        'dateBlogsActual' => $this->newsFromBlog()[4],
                        'rest' => $this->newsFromBlog()[5]

                */

        ]);

    }

    protected function featuredProducts() {
        $zagalovokViewTovars = 'Все товары';
        //Это массив объектов всей таблицы ТОВАРОВ БД, поэтому $this->featuredProducts()[0]->price
        $massivTovars = DB::table('mezon_domik')->get();

        $massivIdTovar = DB::table('mezon_domik')->pluck('id');

        $massivFeaturedProducts = [$zagalovokViewTovars, $massivTovars, $massivIdTovar];
        return $massivFeaturedProducts;
    }




}

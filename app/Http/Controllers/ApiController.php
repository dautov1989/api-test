<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

/*Resources*/
use App\Http\Resources\Products as ProductsResource;
use App\Http\Resources\Statuses as StatusesResource;
use App\Http\Resources\Users as UsersResource;
use App\Http\Resources\Order as OrderResource;
/*Resources*/

/*Models*/
use App\Product;
use App\Status;
use App\User;
use App\Order;
/*Models*/

class ApiController extends Controller
{
    //
    public function getProducts()
    {
    	$products = Product::all();

    	if( !$products->isEmpty() )
    	{
    		return new ProductsResource($products);
    	}else
    	{
    		$message = [
    			"status" => 205,
    			"message" => "В БД товаров нет!",
    		];
    		return $message;
    	}
    }

    public function getStatuses()
    {
    	$status = Status::all();

    	if( !$status->isEmpty() )
    	{
    		return new ProductsResource($status);
    	}else
    	{
    		$message = [
    			"status" => 205,
    			"message" => "В БД статусов нет!",
    		];
    		return $message;
    	}
    }

    public function getUsers()
    {
    	$user = User::all();

    	if( !$user->isEmpty() )
    	{
    		return new ProductsResource($user);
    	}else
    	{
    		$message = [
    			"status" => 205,
    			"message" => "В БД пользователей нет!",
    		];
    		return $message;
    	}
    }

    public function getOrder( Request $request )
    {

    	$orders = Order::where('user_id', $request->user_id)->get();

    	if( !$orders->isEmpty() )
    	{
    		
    		foreach( $orders as $order )
    		{
    			$products = Product::whereIn('id', explode(',', $order->product_id))->get();

    			$data[] = [
	    			'data' => [
	    				'id' => $order->id,
		    			'status' => $order->status->name,
		    			'user' => [
		    				'id' => $order->user->id,
		    				'name' => $order->user->name,
		    			],
		    			'products' => ($products->isEmpty()) ? 'Товаров в заказе нет!' : $products,
		    			'created_at' => (string) $order->created_at,
		            	'updated_at' => (string) $order->updated_at,
	    			],
	    			'status' => 200,
	    			'message' => 'ok'
	    		];
    		}
    		
    		return $data;

    	}else
    	{
    		$message = [
    			"status" => 205,
    			"message" => "В БД заказов нет!",
    		];
    		return $message;
    	}
    }

    public function addOrder( Request $request )
    {
    	#Валидация
    	$rules = [
            'user_id' => 'required|integer',
            'product_id' => 'required'
        ];
        $validator = Validator::make($request->all(),$rules);

        #Если пользователь не прошел валидацию
        if( $validator->fails() )
        {
        	$message = [
    			"status" => 201,
    			"message" => $validator->errors()
    		];
    		return $message;
        }

    	#Поверка есть ли такой пользователь
    	$user = User::find( $request->user_id );
    	$message = [
			"status" => 205,
			"message" => "This user does not exist"
		];
    	if( empty($user) ) return $message;

    	#Поверка есть ли такой товар
    	$product_id = explode(',', $request->product_id);
    	$product = Product::whereIn( 'id', $product_id )->get();
    	$message = [
			"status" => 205,
			"message" => "This product does not exist"
		];
    	if( $product->isEmpty() ) return $message;

    	#Добавление заказа
    	$order = new Order();
		$order->status_id = 1;
		$order->user_id = $request->user_id;
		$order->product_id = $request->product_id;

		if( $order->save() )
		{
			$message = [
				"status" => 200,
				"message" => "A new order was created"
			];
			return $message;
		}else
		{
			$message = [
				"status" => 205,
				"message" => "Unable to create order"
			];
			return $message;
		}
    }

    public function editOrder( Request $request )
    {
    	#Валидация
    	$rules = [
            'order_id' => 'required|integer',
            'status_id' => 'required|integer',
            'user_id' => 'required|integer',
            'product_id' => 'required'
        ];
        $validator = Validator::make($request->all(),$rules);

        #Если пользователь не прошел валидацию
        if( $validator->fails() )
        {
        	$message = [
    			"status" => 201,
    			"message" => $validator->errors()
    		];
    		return $message;
        }

        #Поверка есть ли такой пользователь
    	$user = User::find( $request->user_id );
    	$message = [
			"status" => 205,
			"message" => "This user does not exist"
		];
    	if( empty($user) ) return $message;

    	#Поверка есть ли такой товар
    	$product_id = explode(',', $request->product_id);
    	$product = Product::whereIn( 'id', $product_id )->get();
    	$message = [
			"status" => 205,
			"message" => "This product does not exist"
		];
    	if( $product->isEmpty() ) return $message;

    	#Поверка есть ли такой заказ
    	$order = Order::find($request->order_id);
    	$message = [
			"status" => 205,
			"message" => "This order does not exist"
		];
    	if( empty($order) ) return $message;

    	#Поверка есть ли такой такой статус
    	$status = Status::find($request->status_id);
    	$message = [
			"status" => 205,
			"message" => "This status does not exist"
		];
    	if( empty($status) ) return $message;

    	if( strtolower($order->status_id) == 3 )
    	{
    		if( $order->save() )
    		{
    			$message = [
	    			"status" => 206,
	    			"message" => 'Данный заказ имеет стату как оплачен. Вы не можете его изменять'
	    		];
	    		return $message;
    		}
    		
    	}else
    	{
    		$order->status_id = $request->status_id;
    		$order->user_id = $request->user_id;
    		$order->product_id = $request->product_id;
    		if( $order->save() )
    		{
    			if( $request->status_id == 3 )
    			{
    				/* отправка емайл и смс логика */
    				/* отправка емайл и смс логика */
    			}

    			$message = [
	    			"status" => 200,
	    			"message" => 'Заказ был успешно изменен'
	    		];
	    		return $message;
    		}
    	}
    }
}

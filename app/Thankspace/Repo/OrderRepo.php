<?php namespace Thankspace\Repo;

class OrderRepo extends BaseRepo
{
	
	public function __construct(\Order $order)
	{
		$this->model = $order;
	}
	
	
	public function getInvoiceList(array $input = array())
	{
		$user_id = ( isset($input['user_id']) ? $input['user_id'] : \Auth::user()->id );
		
		$order = \Order::where('user_id', $user_id)
					->with('OrderSchedule', 'OrderPayment', 'ReturnSchedule')
					->paginate(20);
		
		if ( $order ) {
			return $order;
		} else {
			return false;
		}
	}
	
	public function confirmPayment(array $input = array())
	{
		$status = ( \Auth::user()->type == 'admin' ? 2 : 1 );
		$confirm = \OrderPayment::whereIn('id', $input['order_payment_id'])->update([ 'status' => $status ]);
		if ( $confirm )
		{
			return $confirm;
		} else {
			$this->setErrors('No invoice selected');
			return false;
		}
	}
}
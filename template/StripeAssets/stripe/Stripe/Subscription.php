<?php

class Stripe_Subscription extends Stripe_ApiResource
{
  /**
   * @return string The API URL for this Stripe subscription.
   */
  public function instanceUrl()
  {
    $id = $this['id'];
    $customer = $this['customer'];
    if (!$id) {
      throw new Stripe_InvalidRequestError(
          "Could not determine which URL to request: " .
          "class instance has invalid ID: $id",
          null
      );
    }
    $id = Stripe_ApiRequestor::utf8($id);
    $customer = Stripe_ApiRequestor::utf8($customer);

    $base = self::classUrl('Stripe_Customer');
    $customerExtn = urlencode($customer);
    $extn = urlencode($id);
    return "$base/$customerExtn/subscriptions/$extn";
  }

  public static function retrieve($id, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedRetrieve($class, $id, $apiKey);
  }

  /**
   * @param array|null $params
   * @param string|null $apiKey
   *
   * @return Stripe_Plan The created plan.
   */
  public static function create($params=null, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedCreate($class, $params, $apiKey);
  }

  /**
   * @param array|null $params
   *
   * @return Stripe_Plan The deleted plan.
   */
  public function delete($params=null)
  {
    $class = get_class();
    return self::_scopedDelete($class, $params);
  }
  
  /**
   * @return Stripe_Plan The saved plan.
   */
  public function save()
  {
    $class = get_class();
    return self::_scopedSave($class);
  }
  
  /**
   * @param array|null $params
   * @param string|null $apiKey
   *
   * @return array An array of Stripe_Plans.
   */
  public static function all($params=null, $apiKey=null)
  {
    $class = get_class();
    return self::_scopedAll($class, $params, $apiKey);
  }

  /**
   * @return Stripe_Subscription The updated subscription.
   */
  // public static function update($params=null, $apiKey=null)
  //   {
  //     $class = get_class();
  //     return self::_scopedUpdate($class, $params, $apiKey);
  //   }

  public function deleteDiscount()
  {
    $requestor = new Stripe_ApiRequestor($this->_apiKey);
    $url = $this->instanceUrl() . '/discount';
    list($response, $apiKey) = $requestor->request('delete', $url);
    $this->refreshFrom(array('discount' => null), $apiKey, true);
  }
}

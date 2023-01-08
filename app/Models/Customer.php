<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        "customerCode",
        "gstNumber",
        "customerName",
        "billingAddress",
        "shippingAddress",
        "contactPersonName",
        "primaryContactNumber",
        "secondaryContactNumber",
        "email",
        "remark"
    ];
}

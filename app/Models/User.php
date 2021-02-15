<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject {
    use Authenticatable, Authorizable;
    use Traits\Roles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [
       
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'email'           => $this->email,
            'contact'         => $this->contact,
            'confirmed'       => $this->confirmed,
            
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    //Relationship between Admin and ShopOwner
    // public function AdminShopOwner()
    // {
    //     return $this->hasMany(User::class);
    // }
    // public function ShopOwnerAdmin()
    // {
    //     return $this->belongsTo(User::class, 'id');
    // }


     //Relationship between ShopOwner and Manager
     public function ShopOwnerManager()
     {
         return $this->hasMany(User::class, 'shop_owner_id')->whereHas('role', function ($query) {
            $query->where('name', 'ShopManager')->distinct();
        });
     }
	 public function ShopOwnerOperator()
     {
         return $this->hasMany(User::class, 'shop_owner_id')->whereHas('role', function ($query) {
            $query->where('name', 'operator')->distinct();
        });
     }
     public function ManagerShopOwner()
     {
         return $this->belongsTo(User::class, 'shop_owner_id');
     }

     //Relationship between ShopOwner and cachier
     public function ShopOwnerCachier()
     {
         return $this->hasMany(User::class, 'shop_owner_id')->whereHas('role', function ($query) {
            $query->where('name', 'cachier')->distinct();
        });
     }
     public function CachierShopOwner()
     {
         return $this->belongsTo(User::class,'shop_owner_id');
     }

     
    public function shop()
    {
        return $this->hasOne(Shop::class, 'shop_owner_id');
    }

    public function CachierChain()
    {
        return $this->belongsTo(Chain::class,'shop_owner_id');
    }

    public function ManagerChain()
    {
        return $this->hasOne(Chain::class,'manager_id');
    }   

    public function Products()
    {
        return $this->hasMany(Product::class, 'shop_owner_id');
    }
	public function chain()
    {
        return $this->hasOne(User::class, 'chain_id');
    } 

    // public function getShopsThroughOrder()
    // {
    //     return $this->hasManyThrough(User::class, Order::class, 'supplier_id', 'id', 'id', 'shop_owner_id');
    // }

    public function Categories($chain_id)
    {
        return $this->hasManyThrough(Category::class, ProductBase::class,'shop_owner_id','id','id','category_id')
        ->where('product_base.chain_id','=',$chain_id)->distinct(); 
    }

    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class,'shop_owner_id');
    }

    public function supplier()
    {
        return $this->belongsToMany(Supplier::class,'shop_supplier','shop_id','supplier_id')->withTimestamps();
    }
 
    public function user_menu()
    {
        return $this->hasMany(Menu::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $primaryKey = 'bill_quoteid';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['bill_quoteid'];
    const CREATED_AT = 'bill_created';
    const UPDATED_AT = 'bill_updated';

    /**
     * relatioship business rules:
     *         - the Creator (user) can have many Quotes
     *         - the Quote belongs to one Creator (user)
     */
    public function creator() {
        return $this->belongsTo('App\Models\User', 'bill_creatorid', 'id');
    }

    /**
     * relatioship business rules:
     *         - the Quote belongs to one Client
     */
    public function client() {
        return $this->belongsTo('App\Models\Client', 'bill_clientid', 'client_id');
    }

    /**
     * relatioship business rules:
     *         - the Quote belongs to one Project
     */
    public function project() {
        return $this->belongsTo('App\Models\Project', 'bill_projectid', 'project_id');
    }

    /**
     * relatioship business rules:
     *         - the Category can have many Quotes
     *         - the Quote belongs to one Category
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'bill_categoryid', 'category_id');
    }

    /**
     * relatioship business rules:
     *         - the Quote can have many Lineitems
     *         - the Lineitem belongs to one Quote
     *         - other Lineitems can belong to other tables
     */
    public function lineitems() {
        return $this->morphMany('App\Models\Lineitem', 'lineitemresource');
    }

    /**
     * relatioship business rules:
     *         - the Quote can have many Payments
     *         - the Payment belongs to one Quote
     */
    public function payments() {
        return $this->hasMany('App\Models\Payment', 'payment_quoteid', 'bill_quoteid');
    }

    /**
     * relatioship business rules:
     *         - the Quote can have many Tags
     *         - the Tags belongs to one Quote
     *         - other tags can belong to other tables
     */
    public function tags() {
        return $this->morphMany('App\Models\Tag', 'tagresource');
    }

    /**
     * display format for quote id - adding leading zeros & with any set prefix
     * formatted_bill_quoteid
     * e.g. INV-000001
     */
    public function getFormattedBillQuoteidAttribute() {
        return runtimeQuoteIdFormat($this->bill_quoteid);
    }

    /**
     */
    public function taxes() {
        return $this->morphMany('App\Models\Tax', 'taxresource');
    }

}

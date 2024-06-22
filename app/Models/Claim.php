<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_number',
        'claim_number',
        'claim_date',
        'incident_date',
        'amount_claimed',
        'amount_approved',
        'status',
        // Add other fillable attributes here
    ];

    // Append the 'agent_name' attribute
    protected $appends = ['agent_name'];

    // Accessor method to get agent_name
    public function getAgentNameAttribute()
    {
        // Assuming 'agent_id' is the foreign key linking to the 'id' column in the 'users' table
        return $this->user->name ?? null;
    }

    // Relationship to User model for agent
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Idea extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'category_id',
        'user_id',
        'status_id',
        'title',
        'slug',
        'description',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['category'] ?? false, function ($query, $category) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category);
            });
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->whereHas('status', function ($query) use ($status) {
                $query->where('name', ucfirst($status));
            });
        });


        $query->when($filters['filter'] ?? false, function ($query, $filter) {
            if ($filter == 'top_voted') {
                $query->orderBy('votes_count', 'desc');
            } elseif ($filter == 'my_ideas' && auth()->check()) {
                $query->where('user_id', auth()->id());
            } elseif ($filter == 'spams') {
                $query->where('spams', '>', 0)->orderbyDesc('spams');
            }
        });
    }


    // creating event

    protected static function boot()
    {

        parent::boot();
        static::creating(function ($idea) {
            $idea->user_id = auth()->id();
            $idea->status_id = 1;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function votes()
    {
        return $this->belongsToMany(User::class, 'votes', 'idea_id', 'user_id');
    }


    //helper methods

    public function vote()
    {
        $this->votes()->attach(auth()->id());
    }

    public function removeVote()
    {
        $this->votes()->detach(auth()->id());
    }

    public function isVotedByUser()
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return $this->votes()->where('user_id', $user->id)->exists();
    }
}

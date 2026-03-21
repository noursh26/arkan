<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\RulingTopic;
use App\Models\IslamicRuling;

class IslamicRulingController extends ApiController
{
    public function topics()
    {
        $data = Cache::remember('ruling_topics', 3600, function () {
            return RulingTopic::withCount(['rulings' => fn($q) => $q->where('is_active', true)])
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'icon' => $t->icon, 'rulings_count' => $t->rulings_count]);
        });

        return $this->success($data);
    }

    public function index(Request $request)
    {
        $query = IslamicRuling::with('topic:id,name')
            ->where('is_active', true)
            ->when($request->topic_id, fn($q) => $q->where('topic_id', $request->topic_id))
            ->when($request->search, fn($q) => $q->where('question', 'like', "%{$request->search}%"));

        $paginated = $query->paginate(10);

        return $this->success([
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'per_page'     => $paginated->perPage(),
            'total'        => $paginated->total(),
            'items'        => $paginated->map(fn($r) => [
                'id'       => $r->id,
                'topic'    => $r->topic ? ['id' => $r->topic->id, 'name' => $r->topic->name] : null,
                'question' => $r->question,
                'answer'   => $r->answer,
                'evidence' => $r->evidence,
            ]),
        ]);
    }
}

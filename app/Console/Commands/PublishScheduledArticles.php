<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';
    protected $description = 'Publish articles that are scheduled for publication';

    public function handle()
    {
        $articles = Article::where('status', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get();

        foreach ($articles as $article) {
            $article->update(['status' => 'published']);

            User::chunk(100, function ($users) use ($article) {
                foreach ($users as $user) {
                    Notification::notify(
                        $user->id,
                        'new_article',
                        $article->title,
                        'A new article has been published.',
                        route('articles.index')
                    );
                }
            });
        }

        $this->info("Published {$articles->count()} scheduled article(s).");

        return 0;
    }
}

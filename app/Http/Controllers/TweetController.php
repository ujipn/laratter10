<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Auth;
use App\Services\TweetService;

class TweetController extends Controller
{

    protected $tweetService;

    public function __construct(TweetService $tweetService)
  {
    $this->tweetService = $tweetService;
  }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Tweet::class);
        // $tweets = Tweet::with('user')->latest()->get();
        // return view('tweets.index', compact('tweets'));

        $tweets = $this->tweetService->allTweets();
    return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Tweet::class);
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Tweet::class);
  
      
        //   $request->user()->tweets()->create($request->only('tweet'));
      
        //   return redirect()->route('tweets.index');

        $tweet = $this->tweetService->createTweet($request->only('tweet'), $request->user());

        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        $this->authorize('view', $tweet);
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        $this->authorize('update', $tweet);
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $this->authorize('update', $tweet);
        // $request->validate([
        //     'tweet' => 'required|max:255',
        //   ]);
      
        //   $tweet->update($request->only('tweet'));
      
        //   return redirect()->route('tweets.show', $tweet);

        $updatedTweet = $this->tweetService->updateTweet($tweet, $request->all());

        return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);
        // $tweet->delete();

        // return redirect()->route('tweets.index');

        $this->tweetService->deleteTweet($tweet);

        return redirect()->route('tweets.index');
    }
}

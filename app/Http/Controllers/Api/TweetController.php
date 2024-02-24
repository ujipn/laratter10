<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use Illuminate\Http\Request;
// 🔽 追加

use App\Models\Tweet;
use App\Services\TweetService;

class TweetController extends Controller
{
  /**
   * Display a listing of the resource.
   */

   protected $tweetService;

   public function __construct(TweetService $tweetService)
  {
    $this->tweetService = $tweetService;
  }

  public function index()
  {
    $this->authorize('viewAny', Tweet::class);
    // $tweets = Tweet::with('user')->latest()->get();
    // return response()->json($tweets);
    $tweets = $this->tweetService->allTweets();
    return response()->json($tweets);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $this->authorize('create', Tweet::class);
    // $tweet = $request->user()->tweets()->create($request->only('tweet'));
    // return response()->json($tweet, 201);
    $tweet = $this->tweetService->createTweet($request->only('tweet'), $request->user());
    return response()->json($tweet, 201);

  }

  /**
   * Display the specified resource.
   */
  public function show(Tweet $tweet)
  {
    $this->authorize('view', $tweet);
    return response()->json($tweet);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Tweet $tweet)
  {
    $this->authorize('update', $tweet);
    // $request->validate([
    //   'tweet' => 'required|string|max:255',
    // ]);

    // $tweet->update($request->all());

    // return response()->json($tweet);

    $updatedTweet = $this->tweetService->updateTweet($tweet, $request->all());

    return response()->json($updatedTweet);

  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Tweet $tweet)
  {
    $this->authorize('delete', $tweet);
    // $tweet->delete();
    // return response()->json(['message' => 'Tweet deleted successfully']);

    $this->tweetService->deleteTweet($tweet);
    return response()->json(['message' => 'Tweet deleted successfully']);
  }
}


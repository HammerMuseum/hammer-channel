<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\Newsletter;

/**
 * Class NewsletterController
 * @package App\Http\Controllers
 *
 * Responsible for handling email sign-ups
 *
 */
class NewsletterController extends Controller
{
    /** @var Newsletter */
    protected $newsletter;

    /**
     * NewsletterController constructor.
     * @param Newsletter $newsletter
     */
    public function __construct(
        Newsletter $newsletter
    ) {
        $this->newsletter = $newsletter;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function submit(Request $request)
    {
        try {
            $this->validate($request, [
                'data.email' => 'required|email',
            ]);
        } catch (\Exception $e) {
            // Email address is invalid
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ]);
        }
        $requestData = $request->get('data');
        // Email address is valid
        $signupAction = $this->newsletter->subscribe($requestData['email'], [
            'FNAME' => $requestData['firstname'],
            'LNAME' => $requestData['lastname']
        ], 'subscribers');
        if ($signupAction) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'You are already subscribed to this mailing list.'
        ]);
    }
}

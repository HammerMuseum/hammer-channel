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
     */
    public function submit(Request $request)
    {
        $emailAddress = $request->get('email');
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            // Email address is invalid
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ]);
        } else {
            // Email address is valid
            $firstName = $request->get('firstname');
            $lastName = $request->get('lastname');
            $signupAction = $this->newsletter->subscribe($emailAddress, [
                'FNAME' => $firstName,
                'LNAME' => $lastName
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
}

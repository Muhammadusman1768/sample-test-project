<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * Get bookings based on user_id or user_type.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id');
        $user_type = $request->__authenticatedUser->user_type;

        try {
            $response = $this->repository->getBookings($user_id, $user_type);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific booking by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $job = $this->repository->getBookingWithTranslator($id);
            return response()->json($job);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new booking.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        try {
            $response = $this->repository->createBooking($user, $data);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a booking by ID.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        try {
            $response = $this->repository->updateBooking($id, $data, $user);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send email for immediate job booking.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function immediateJobEmail(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->repository->sendImmediateJobEmail($data);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get job history for a specific user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getHistory(Request $request)
    {
        $user_id = $request->get('user_id');

        try {
            $response = $this->repository->getUserJobHistory($user_id, $request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Accept a job by a translator.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        try {
            $response = $this->repository->acceptJob($data, $user);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Accept a job with its ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        try {
            $response = $this->repository->acceptJobWithId($data, $user);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Cancel a job by a translator or customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        try {
            $response = $this->repository->cancelJob($data, $user);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * End a job by a translator.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function endJob(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->repository->endJob($data);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark the customer as "not called" for a job.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerNotCall(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->repository->markCustomerNotCalled($data);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get potential jobs for a translator.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPotentialJobs(Request $request)
    {
        $user = $request->__authenticatedUser;

        try {
            $response = $this->repository->getPotentialJobs($user);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update distance and job related data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function distanceFeed(Request $request)
    {
        $data = $request->all();

        try {
            $distance = isset($data['distance']) ? $data['distance'] : '';
            $time = isset($data['time']) ? $data['time'] : '';
            $jobid = isset($data['jobid']) ? $data['jobid'] : '';
            $session = isset($data['session_time']) ? $data['session_time'] : '';
            $admincomment = isset($data['admincomment']) ? $data['admincomment'] : '';
            $flagged = isset($data['flagged']) && $data['flagged'] === 'true' ? 'yes' : 'no';
            $manually_handled = isset($data['manually_handled']) && $data['manually_handled'] === 'true' ? 'yes' : 'no';
            $by_admin = isset($data['by_admin']) && $data['by_admin'] === 'true' ? 'yes' : 'no';

            if ($time || $distance) {
                Distance::where('job_id', $jobid)->update(['distance' => $distance, 'time' => $time]);
            }

            if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {
                Job::where('id', $jobid)->update([
                    'admin_comments' => $admincomment,
                    'flagged' => $flagged,
                    'session_time' => $session,
                    'manually_handled' => $manually_handled,
                    'by_admin' => $by_admin,
                ]);
            }

            return response()->json(['message' => 'Record updated!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Reopen a job.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reopen(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->repository->reopenJob($data);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Resend notifications for a job.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendNotifications(Request $request)
    {
        $data = $request->all();

        try {
            $this->repository->resendNotifications($data);
            return response()->json(['success' => 'Push sent']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Resend SMS notifications for a job.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();

        try {
            $this->repository->resendSMSNotifications($data);
            return response()->json(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

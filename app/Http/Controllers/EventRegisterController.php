<?php

namespace App\Http\Controllers;

use Response;
use App\Models\User;
use App\Models\Event;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Validator;
use App\DataTables\EventRegisterDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EventRegisterRepository;
use App\Http\Requests\CreateEventRegisterRequest;
use App\Http\Requests\UpdateEventRegisterRequest;

class EventRegisterController extends AppBaseController
{
    /** @var EventRegisterRepository $eventRegisterRepository*/
    private $eventRegisterRepository;

    public function __construct(EventRegisterRepository $eventRegisterRepo)
    {
        $this->eventRegisterRepository = $eventRegisterRepo;
    }

    /**
     * Display a listing of the EventRegister.
     *
     * @param EventRegisterDataTable $eventRegisterDataTable
     *
     * @return Response
     */
    public function index(EventRegisterDataTable $eventRegisterDataTable)
    {
        return $eventRegisterDataTable->render('event_registers.index');
    }

    /**
     * Show the form for creating a new EventRegister.
     *
     * @return Response
     */
    public function create()
    {
        $events = Event::query()->select('title', 'id')->get();
        $users = User::query()->select('name', 'id')->get();

        return view('event_registers.create')->with('events', $events)->with('users', $users);
    }

    /**
     * Store a newly created EventRegister in storage.
     *
     * @param CreateEventRegisterRequest $request
     *
     * @return Response
     */
    public function store(CreateEventRegisterRequest $request)
    {
        // $input = $request->all();
        // $eventRegister = $this->eventRegisterRepository->create($input);
        // Flash::success('Event Register saved successfully.');
        // return redirect(route('eventRegisters.index'));

        $input = $request->all();
        $eventRegister = $this->eventRegisterRepository->create($input);


        $validator  = Validator::make($request->all(), [
            'foto' => 'required|image'
        ],);
        if ($validator->fails()) {
            Flash::danger('Gambar harus diisi.');
        }
        if ($request->file('foto')) {
            
            $file = $request->foto->store('assets/event_register', 'public');     
             $eventRegister ->foto = $file;
            $eventRegister->update();
        }

        Flash::success('Event Register saved successfully.');

        return redirect(route('eventRegisters.index'));
    }

    /**
     * Display the specified EventRegister.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $eventRegister = $this->eventRegisterRepository->find($id);

        if (empty($eventRegister)) {
            Flash::error('Event Register not found');

            return redirect(route('eventRegisters.index'));
        }

        return view('event_registers.show')->with('eventRegister', $eventRegister);
    }

    /**
     * Show the form for editing the specified EventRegister.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $eventRegister = $this->eventRegisterRepository->find($id);
        $events = Event::query()->pluck('title', 'id');
        $users = User::query()->pluck('name', 'id');

        if (empty($eventRegister)) {
            Flash::error('Event Register not found');

            return redirect(route('eventRegisters.index'));
        }

        return view('event_registers.edit')->with('eventRegister', $eventRegister)->with('events', $events)->with('users', $users);
    }

    /**
     * Update the specified EventRegister in storage.
     *
     * @param int $id
     * @param UpdateEventRegisterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventRegisterRequest $request)
    {
        $eventRegister = $this->eventRegisterRepository->find($id);

        if (empty($eventRegister)) {
            Flash::error('Event Register not found');

            return redirect(route('eventRegisters.index'));
        }

        $eventRegister = $this->eventRegisterRepository->update($request->all(), $id);

        Flash::success('Event Register updated successfully.');

        return redirect(route('eventRegisters.index'));
    }

    /**
     * Remove the specified EventRegister from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $eventRegister = $this->eventRegisterRepository->find($id);
        // dd($id);

        if (empty($eventRegister)) {
            Flash::error('Event Register not found');

            return redirect(route('eventRegisters.index'));
        }

        $this->eventRegisterRepository->delete($id);

        Flash::success('Event Register deleted successfully.');

        return redirect(route('eventRegisters.index'));
    }
}

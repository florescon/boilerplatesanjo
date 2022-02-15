<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function destroy(Ticket $ticket)
    {
        if($ticket->id){
            $ticket->delete();
        }

        return redirect()->back()->withFlashSuccess(__('The ticket was successfully deleted.'));
    }

}

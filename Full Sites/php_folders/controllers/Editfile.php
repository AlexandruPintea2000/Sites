<?php

include_once "Controller.php";
include_once "models/Editfile.php";



class Editfile extends Controller 
{

    private function get_edifile_through_id( $id )
    {
        $editfiles = get_editfiles();

        foreach ( $editfiles as $i )
            if ( $i->get_id() == $id )
                return $i;
    }



    public function view ( $id ) 
    {
        $editfile = $this->get_edifile_through_id( $id );

        $data = [
            'title'=>$editfile->get_fname() . ' ' .  $editfile->get_lname() . " - Details",
            'email'=>$editfile->get_email()
        ];
        $this->loadView( "editfile", "view", $data);
    }





    public function delete( $id ) 
    {
        $editfile = $this->get_edifile_through_id( $id );

        $data = [
            'title'=>$editfile->get_fname() . ' ' .  $editfile->get_lname() . " - Delete",
            'name'=>$editfile->get_fname() . ' ' .  $editfile->get_lname(),
            'email'=>$editfile->get_email(),
            'editfile_delete_address'=>'"index.php?f=editfile/delete_through_id/' . $editfile->get_id() . '"'
        ];

        $this->loadView( "editfile", "delete", $data);
    }

    public function delete_through_id( $id )
    {
        $db = new Database( "localhost", "root", "", "db" );
        $table = new Table( "emails", $db );

        $table->remove_row_through_id( $id );

        $this->index();
    }




    public function create() 
    {
        //incarcare lista editi
        //apelare metoda print edit
        $data = [
            'title'=> "Make editfile",
        ];
        $this->loadView( "editfile", "create", $data);
    }

    public function create_editfile( $data )
    {
        $row = explode( '(@)', $data );

        $db = new Database( "localhost", "root", "", "db" );
        $table = new Table( "emails", $db );

        $table->add_row( $row );

        $this->index();
    }






    public function edit( $id ) 
    {
        $editfile = $this->get_edifile_through_id( $id );

        $data = [
            'title'=> $editfile->get_fname() . ' ' . $editfile->get_lname() . ' - Edit',
            'id'=> $editfile->get_id(),
            'fname'=> $editfile->get_fname(),
            'lname'=> $editfile->get_lname(),
            'email'=> $editfile->get_email()
        ];
        $this->loadView( "editfile", "edit", $data);
    }

    public function edit_editfile( $data )
    {
        $row = explode( '(@)', $data );

        $db = new Database( "localhost", "root", "", "db" );
        $table = new Table( "emails", $db );

        $table->update_row( $row );

        $this->index();
    }





    public function index() 
    {
        $this->editfiles = $editfiles = get_editfiles();

        $data = [
            'editfiles' => $this->editfiles
        ];

        $this->loadView( "editfile", "index", $data);
    }
}
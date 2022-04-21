<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class PostCrud extends Component
{

    public $posts, $title, $desc, $post_id;
    public $isModalOpen = 0;

    public function render()
    {
        $this->posts = Post::all();
        return view('livewire.post-crud');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->title = '';
        $this->body = '';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|max:50',
            'body' => 'required',
        ]);

        Post::updateOrCreate([
            'id' => $this->post_id
        ], [
            'title' => $this->title,
            'body' => $this->body
        ]);

        session()->flash('message', $this->post_id ? 'Data updated successfully.' : 'Data added successfully.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->post_id = $id;
        $this->title = $post->title;
        $this->body = $post->body;

        $this->openModal();
    }

    public function delete($id)
    {
        Post::find($id)->delete();
        session()->flash('message', 'Data deleted successfully.');
    }
}

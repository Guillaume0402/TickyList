<?php
namespace App\Controllers;
use App\Http\AbstractController;


final class ProjectController extends AbstractController
{
     public function projects(): string
    {
        return $this->render('pages/projects', [
            'pageTitle' => 'Projets',
            'title' => 'Projets',
            'subtitle' => 'Page projets OK',
            'projects' => $this->getProjects(),
        ]);
    }

    private function getProjects(): array
    {
        $repo = new \App\Repositories\ProjectRepository();
        return $repo->findActiveWithStatsByUserId($_SESSION['user_id']);
    }
}
?>
<?php

use App\TwigConfig;
use App\PostData;
use App\Models\Database;
use App\Models\Photos;
use App\Helpers\Functions;
use App\Helpers\MenuGenerator;


class BodyController 
{
    private $db;
    private $data;
    private $photos;
    private $menu;

    public function __construct()
    {
        $this->db = new Database();
        $this->data = new PostData();
        $this->photos = new Photos();
        $this->menu = $this->generateMenu();
    }

    //generowanie menu w bocznym sidebarze
    private function generateMenu()
    {
        $menuData = $this->db->find('menu');
        $menuGenerator = new MenuGenerator($menuData);
        return $menuGenerator->generateMenu();
    }

    //nagłówek tak aby uzytkownik wiedział gdzie dodaje rekordy
    public function getSectionTitle($id) {
        $secionTitle = $this->db->find('menu', ['id' => $id], ['title'], true);
        if ($secionTitle) {
            return $secionTitle['title'];
        }
        return null;
    }

    //po wejsciu w 'body', wyświetla wszystkie rekordy z danego menu w jednej tabeli
    public function home($id)
    {
        $items = $this->db->find('body', ['parent_id' => $id['id']], null, false, 'sorting ASC');

        echo TwigConfig::getTwig()->render('admin/body/body.twig', [
            'items' => $items,
            'section' => $id['id'],
            'menu' => $this->menu,
            'sectionName' => $this->getSectionTitle($id['id'])
        ]);
    }
    
    //zapisywanie nowego rekordu
    public function create($id)
    {
        $errorMessages = [];
        $editData = [];
    
        if (isset($_POST['submit'])) {
            $postItems = $this->data->getPostDataBody();
    
            if (!empty($postItems['errors'])) {
                $errorMessages = $postItems['errors'];
                $editData = $postItems['data'];
            } else {
                $this->db->save('body', $postItems['data']);
                header('Location: /admin/body/'.$id['id']);
                exit;
            }
        }
    
        echo TwigConfig::getTwig()->render('admin/body/bodyForm.twig', [
            'section' => $id['id'],
            'error' => $errorMessages,
            'edit' => $editData, //zapamietaj dane w polach po validaciji
            'menu' => $this->menu,
            'sectionName' => $this->getSectionTitle($id['id'])
        ]);
    }
    

    //edycja rekordu
    public function update($id)
    {
        $errorMessages = [];
        $currentData = $this->db->find('body', ['id' => $id['id']], null, true);
    
        if (isset($_POST['submit'])) {
            $postItems = $this->data->getPostDataBody($currentData);
            if (isset($postItems['errors']) && $postItems['errors']) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->edit('body', $postItems['data'], $id['id']);
                header('Location: /admin/body/' . $currentData['parent_id']);
                exit;
            }
        }
    
        echo TwigConfig::getTwig()->render('admin/body/bodyForm.twig', [
            'section' => $id['id'],
            'edit' => $currentData,
            'formAction' => 'update',
            'error' => $errorMessages,
            'menu' => $this->menu
        ]);
    }
    
    //usuwanie rekordu
    public function destroy($id)
    {
        $record = $this->db->find('body', ['id' => $id['id']], null, true);

        if ($record) {
            $this->photos->deletePhotos($record);
            $this->db->delete('body', ['id' => $id['id']]);
            header('Location: /admin/body/' . $record ['parent_id']);
            exit;
        }
    }

    //przesywanie, sortowanie rekordów w panelu
    public function moveItem($param)
    {
        $currentItem = $this->db->find('body', ['id' => $param['id']], ['id', 'sorting'], true);
        if (!$currentItem) {
            echo json_encode(['success' => false]);
            return;
        }
    
        if ($param['direction'] === 'up') {
            $adjacentItem = $this->db->getItemsBySortingDirection('body', $currentItem['sorting'], 'above');
        } elseif ($param['direction'] === 'down') {
            $adjacentItem = $this->db->getItemsBySortingDirection('body', $currentItem['sorting'], 'below');
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid direction']);
            return;
        }
    
        if ($adjacentItem) {
            $this->swapSorting($currentItem, $adjacentItem);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    //funkcja pomocnicz aktualizujące przesuwane pozycje
    private function swapSorting($item1, $item2)
    {
        $this->db->update('body', ['sorting' => $item2['sorting']], ['id' => $item1['id']]);
        $this->db->update('body', ['sorting' => $item1['sorting']], ['id' => $item2['id']]);
    }

 

}
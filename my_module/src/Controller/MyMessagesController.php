<?php

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
 use Drupal\Core\Link;
 use Drupal\Core\Url;

 use Drupal\Core\Ajax\AjaxResponse;
 use Drupal\Core\Ajax\InvokeCommand;
 use Drupal\Core\Ajax\MessageCommand;

/**
 * Class MyMessagesController
 * @package Drupal\my_module\Controller
 */
class MyMessagesController extends ControllerBase {

    public function index() {
        
        //create table header
        $header_table = array(
        'id' => t('ID'),
        'message' => t('Message'),
        'created' => t('Post Date'),
        'delete' => t('Delete'),
        );

        // get data from database table
        $query = \Drupal::database()->select('my_messages', 'm');
        $query->fields('m', ['id', 'message', 'created']);
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {

            $url_delete = Url::fromRoute('my_module.delete_message', ['id' => $data->id], []);
            $link_options = array(
                'attributes' => array(
                  'class' => array(
                    'use-ajax'
                    ),
                ),
              );
              $url_delete->setOptions($link_options);
            $delete_link = Link::fromTextAndUrl('X', $url_delete);
            $url_view = Url::fromRoute('my_module.show_message', ['id' => $data->id], []);
            $view = Link::fromTextAndUrl('#'. $data->id, $url_view);

            //get data
            $rows[] = array(
                'id' => $view,
                'message' => $data->message,
                'created' => date("Y-m-d H:i:s",  $data->created),
                'delete' => $delete_link
            );
        }
        // render table
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => $rows,
        '#empty' => t('No data found.'),
        '#attributes' => array (
            'class' => array('my-messages'),
          ),
        ];
        return $form;
    }

   /**
   * @return array
   */
    public function showMessage($id) {
  
        $query = \Drupal::database()->select('my_messages', 'm');
        $query->condition('id', $id);
        $query->fields('m', ['id', 'message', 'created']);
        $data = $query->execute()->fetchAssoc();

        $message = $data['message'] ;
        $created = date("F j, Y, g:i a",  $data['created']);

        return [
        '#type' => 'markup',
        '#markup' => "<h1># $id</h1>
                      <h1>$message</h1><br>
                      <p><i>$created</i></p>"
        ];
    }

    /**
   * @Delete row
   */
    public function deleteMessage($id) {
            \Drupal::database()
            ->delete('my_messages')
            ->condition('id', $id)
            ->execute();
    
        $response = new AjaxResponse();
        $response->addCommand(new MessageCommand(t('Message deleted successfully.')));
       
        return $response;
   
    }

}



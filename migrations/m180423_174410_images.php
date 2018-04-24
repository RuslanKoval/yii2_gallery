<?php

use yii\db\Migration;

/**
 * Class m180423_174410_images
 */
class m180423_174410_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%images}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'description' => $this->text(),
            'preview' => $this->string(),
            'gallery_id' => $this->integer(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey("fk_image", "{{%images}}", "gallery_id", "{{%gallery}}", "id");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_image', '{{%images}}');
        $this->dropTable('{{%images}}');
    }

}

<?php
if (!defined('zerp_cmd'))
    die('not allowed');

/**
 * @author Alexander German (zerro)
 */
class mkdb_Model extends Model {
    private $_tables = array();
    private $_FK = array();
    private $_drop = array();
    private $_create = array();

    public function createSchema() {
        foreach (array_keys($this->cfg()->get('_Tables')) as $name) {
                $tableFactory = $this->loadTable($name);
                $this->_tables[$tableFactory->getTableName()] = $tableFactory;
                $this->_drop[$tableFactory->getTableName()] = 'DROP TABLE IF EXISTS `'.$tableFactory->getTableName().'`';
                $this->_create[$tableFactory->getTableName()] = $this->_createTableQuery($tableFactory->getTableName(), $tableFactory->getFieldSet());
        }
       
        foreach ($this->_FK as $FK) {
            $sql = 'ALTER TABLE `'.$FK['table'].'` DROP FOREIGN KEY `FK_'.$FK['table'].'_'.$FK['field'].'_'.$FK['constraint'].'_'.$FK['foreign'].'`';
            echo $sql.PHP_EOL;
            $this->db()->rawQuery($this->_tables[$FK['table']]->getConnection(), $sql, true);
        }

        foreach ($this->_drop as $table=>$sql) {
            echo $sql.PHP_EOL;
            $this->db()->rawQuery($this->_tables[$table]->getConnection(), $sql);
        }

        foreach ($this->_create as $table=>$sql) {
            echo $sql.PHP_EOL;
            $this->db()->rawQuery($this->_tables[$table]->getConnection(), $sql);
            if ($this->_tables[$table]->getInitData()) {
                foreach ($this->_tables[$table]->getInitData() as $values) {
                    echo 'add "'.$this->_tables[$table]->getTableName().'" table data: ';
                    print_r($values);
                    echo PHP_EOL;
                    $this->db()->crud(0)
                            ->table($this->_tables[$table])
                            ->insert($values);
                }
            }
        }

        foreach ($this->_FK as $FK) {
            $sql = 'ALTER TABLE `'.$FK['table'].'` ADD CONSTRAINT `FK_'.$FK['table'].'_'.$FK['field'].'_'.$FK['constraint'].'_'.$FK['foreign'].'`
                FOREIGN KEY `FK_'.$FK['table'].'_'.$FK['field'].'_'.$FK['constraint'].'_'.$FK['foreign'].'` (`'.$FK['field'].'`)
                REFERENCES `'.$FK['constraint'].'` (`'.$FK['foreign'].'`)
                ON DELETE '.($FK['delete']?$FK['delete']:'RESTRICT').' ON UPDATE RESTRICT';
            echo $sql.PHP_EOL.PHP_EOL;
            $this->db()->rawQuery($tableFactory->getConnection(), $sql);
        }
    }

    private function _createTableQuery($table, $def) {
        $lenFields = array('int','tinyint','char','varchar');
        $fieldSet = array();
        $PK = array();
        $IDX = array();
        foreach ($def as $field=>$set) {
            if (!empty($set['PK']))
                $PK[] = 'PRIMARY KEY (`'.$field.'`)';
            if (!empty($set['IDX']))
                $IDX[] = 'KEY `'.$field.'_idx` (`'.$field.'`)';
            if (!empty($set['FK'])) {
                $this->_FK[] = array(
                    'table'     => $table,
                    'field'     => $field,
                    'constraint'=> $set['FK']['table'],
                    'foreign'   => $set['FK']['field'],
                    'delete'    => !empty($set['FK']['delete'])?$set['FK']['delete']:'');
            }
            $fieldSet[] = '`'.$field.'` '.$set['type'].((!empty($set['len'])&&in_array($set['type'], $lenFields))?'('.$set['len'].')':'')
            .(!empty($set['unsigned'])?' unsigned':'')
            .(!empty($set['PK']) || !empty($set['notnull'])?' NOT NULL':'').(!empty($set['default'])?' DEFAULT '.($set['default']=='null'?'NULL':'"'.$set['default'].'"'):'')
            .(!empty($set['ai'])?' AUTO_INCREMENT':'');
        }
        $sql = 'CREATE TABLE IF NOT EXISTS `'.$table.'` ('
        .implode(',', $fieldSet)
        .($PK?','.implode(',', $PK):'')
        .($IDX?','.implode(',', $IDX):'')
        .') ENGINE=InnoDB  DEFAULT CHARSET=utf8';
        return $sql;
    }
}
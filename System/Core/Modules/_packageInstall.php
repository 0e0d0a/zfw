<?php
class System_packageInstall extends packageInstall implements packageInstall_Interface {

    public function addMenuItems() {
        $this->_addModules(array(
            array(
                'url'       => '/user/editProfile/',
                'locale'    => array(
                    1   => 'Profile',
                    2   => 'Профиль пользователя'
                )
            ),
            array(
                'url'       => '/user/changePassword/',
                'locale'    => array(
                    1   => 'Change Password',
                    2   => 'Смена пароля'
                )
            ),
            array(
                array(
                    'url'       => '/accounts/users/',
                    'locale'    => array(
                        1   => 'Accounts list',
                        2   => 'Список пользователей'
                    )
                )
            ),
            array(
                'url'       => '/accounts/groups/',
                'locale'    => array(
                    1   => 'Groups list',
                    2   => 'Список групп'
                )
            ),
            array(
                'url'       => '/accounts/roles/',
                'locale'    => array(
                    1   => 'Roles list',
                    2   => 'Список ролей'
                )
            )
        ));

        $this->_addResources('accounts', array(
            'access', 'editAccounts' ,'editProfile', 'editGroups', 'editRoles'
        ));

        $this->_addSiteLocale('_containers', '_common', array(
                        array(
                            'element'   => 'Ok',
                            'locale'    => array(
                                2   => 'Да'
                            )
                        ),
                        array(
                            'element'   => 'Apply',
                            'locale'    => array(
                                2   => 'Применить'
                            )
                        ),
                        array(
                            'element'   => 'Save',
                            'locale'    => array(
                                2   => 'Сохранить'
                            )
                        ),
                        array(
                            'element'   => 'Cancel',
                            'locale'    => array(
                                2   => 'Отмена'
                            )
                        ),
                        array(
                            'element'   => 'Add',
                            'locale'    => array(
                                2   => 'Добавить'
                            )
                        ),
                        array(
                            'element'   => 'Edit',
                            'locale'    => array(
                                2   => 'Изменить'
                            )
                        ),
                        array(
                            'element'   => 'Remove',
                            'locale'    => array(
                                2   => 'Удалить'
                            )
                        ),
                        array(
                            'element'   => 'Loading',
                            'locale'    => array(
                                2   => 'Загрузка'
                            )
                        ),
                        array(
                            'element'   => 'Allow',
                            'locale'    => array(
                                2   => 'Разрешить'
                            )
                        ),
                        array(
                            'element'   => 'Deny',
                            'locale'    => array(
                                2   => 'Запретить'
                            )
                        ),
                        array(
                            'element'   => 'Home',
                            'locale'    => array(
                                2   => 'Домашняя страница'
                            )
                        ),
                        array(
                            'element'   => 'Collapse/Expand',
                            'locale'    => array(
                                2   => 'Свернуть/развернуть'
                            )
                        ),
                        array(
                            'element'   => 'Not localized yet',
                            'locale'    => array(
                                2   => 'Перевод отсутствует'
                            )
                        ),
                    ));
        $this->_addSiteLocale('_containers', 'guestMain', array(
                        array(
                            'element'   => 'We are Selected',
                            'locale'    => array(
                                2   => 'нас выбирают'
                            )
                        ),
                        array(
                            'element'   => 'Contact',
                            'locale'    => array(
                                2   => 'Связь'
                            )
                        ),
                    ));
        $this->_addSiteLocale('_containers', 'validator', array(
                        array(
                            'element'   => 'can\'t be empty.',
                            'locale'    => array(
                                2   => 'не может быть пустым.'
                            )
                        ),
                        array(
                            'element'   => 'must be integer.',
                            'locale'    => array(
                                2   => 'должен быть целым числом.'
                            )
                        ),
                        array(
                            'element'   => 'must can\'t be negative integer.',
                            'locale'    => array(
                                2   => 'должен быть положительным целым числом.'
                            )
                        ),
                        array(
                            'element'   => 'must be a valid numeric.',
                            'locale'    => array(
                                2   => 'должен быть числом.'
                            )
                        ),
                        array(
                            'element'   => 'must be a string.',
                            'locale'    => array(
                                2   => 'должен быть текстом.'
                            )
                        ),
                        array(
                            'element'   => 'must be valid eMail.',
                            'locale'    => array(
                                2   => 'должен быть реальным eMail адресом.'
                            )
                        ),
                        array(
                            'element'   => 'must be valid IP address.',
                            'locale'    => array(
                                2   => 'должен быть реальным IP адресом.'
                            )
                        ),
                        array(
                            'element'   => 'must be valid date.',
                            'locale'    => array(
                                2   => 'должен быть действительной датой.'
                            )
                        ),
                        array(
                            'element'   => 'is incorrect.',
                            'locale'    => array(
                                2   => 'неверно.'
                            )
                        ),
                        array(
                            'element'   => 'must be greater than %d.',
                            'locale'    => array(
                                2   => 'должен быть более %d.'
                            )
                        ),
                        array(
                            'element'   => 'must be less than %d.',
                            'locale'    => array(
                                2   => 'должен быть менее %d.'
                            )
                        ),
                        array(
                            'element'   => 'must be greater than %d chars.',
                            'locale'    => array(
                                2   => 'должен быть длиннее %d символов.'
                            )
                        ),
                        array(
                            'element'   => 'must be less than %d chars.',
                            'locale'    => array(
                                2   => 'должен быть короче %d символов.'
                            )
                        ),
                        array(
                            'element'   => 'must be equal to',
                            'locale'    => array(
                                2   => 'должен быть равен'
                            )
                        )
                    ));
        $this->_addSiteLocale('auth', 'info', array(
                        array(
                            'element'   => 'You are Logged In as',
                            'locale'    => array(
                                2   => 'Вы вошли как'
                            )
                        ),
                        array(
                            'element'   => 'Sign Out',
                            'locale'    => array(
                                2   => 'Выход'
                            )
                        ),
                        array(
                            'element'   => 'Profile',
                            'locale'    => array(
                                2   => 'Профиль'
                            )
                        ),
                    ));
        $this->_addSiteLocale('auth', 'signIn', array(
                        array(
                            'element'   => 'Have an account',
                            'locale'    => array(
                                2   => 'есть аккаунт'
                            )
                        ),
                        array(
                            'element'   => 'Sign In',
                            'locale'    => array(
                                2   => 'Вход'
                            )
                        ),
                        array(
                            'element'   => 'Sign Up',
                            'locale'    => array(
                                2   => 'Регистрация'
                            )
                        ),
                        array(
                            'element'   => 'Enter to the site',
                            'locale'    => array(
                                2   => 'Вход на сайт'
                            )
                        ),
                        array(
                            'element'   => 'Password',
                            'locale'    => array(
                                2   => 'Пароль'
                            )
                        ),
                        array(
                            'element'   => 'Re-type Password',
                            'locale'    => array(
                                2   => 'Повторите пароль'
                            )
                        ),
                        array(
                            'element'   => 'Forgot password',
                            'locale'    => array(
                                2   => 'Забыли пароль'
                            )
                        ),
                        array(
                            'element'   => 'Sign Up Using',
                            'locale'    => array(
                                2   => 'Регистрация с помощью'
                            )
                        ),
                        array(
                            'element'   => 'Enter using accounts',
                            'locale'    => array(
                                2   => 'Вход с помощью'
                            )
                        ),
                        array(
                            'element'   => 'I agree with',
                            'locale'    => array(
                                2   => 'Я принимаю'
                            )
                        ),
                        array(
                            'element'   => 'Terms of Use',
                            'locale'    => array(
                                2   => 'условия использования'
                            )
                        ),
                    ));
        $this->_addSiteLocale('error', 'deniedPage', array(
                        array(
                            'element'   => 'Permition denied',
                            'locale'    => array(
                                2   => 'В доступе отказано'
                            )
                        ),
                        array(
                            'element'   => 'Go To Home Page',
                            'locale'    => array(
                                2   => 'Перейти на стартовую страницу'
                            )
                        ),
                    ));
        $this->_addSiteLocale('error', 'deniedMessage', array(
                        array(
                            'element'   => 'Permition denied',
                            'locale'    => array(
                                2   => 'В доступе отказано'
                            )
                        ),
                        array(
                            'element'   => 'Go To Home Page',
                            'locale'    => array(
                                2   => 'Перейти на стартовую страницу'
                            )
                        ),
                    ));
    }
}
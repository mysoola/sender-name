<?php

namespace App\Core\Commands\Export;

use App\Core\Models\User;
use App\Core\Export\UsersExport\UsersExportFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UsersExportCommand extends Command
{
    protected function configure()
    {
        $this->setName('export:users')
            ->setDescription('Делает выгрузку из таблицы пользователей в csv и xml в папку public/upload/export/users')
            ->setHelp('Параметры:' . PHP_EOL . 'file_extension - обязательный, расширение файла - csv или xml')
            ->addArgument('file_extension');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileExtension = $input->getArgument('file_extension');

        if (!$fileExtension) {
            throw new \Exception('Необходимо передать первым аргументом расширение файла - csv или xml');
        }

        $arUsers = new User();
        $arUsers = $arUsers->getAllUsers();

        if (!empty($arUsers)) {
            $factory = new UsersExportFactory();
            $usersExportObj = $factory->exportUsers($fileExtension);
            $result = $usersExportObj->exportUsers($arUsers);

            if ($result === true) {
                $output->writeln('Файл сформирован');
            } else {
                $output->writeln('Не удалось записать файл');
            }
        } else {
            $output->writeln('Файл не сформирован, пришли пустые данные');
        }
    }
}

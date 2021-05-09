<?php

namespace Steellg0ld\Museum\forms;

use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\ModalForm;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class CodeForm
{
    public static function open(MPlayer $player)
    {
        {
            $form = new ModalForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        switch ($data) {
                            case 1:
                                if ($p->enterCodeWaitEnd >= time()) {
                                    self::enterCode($p);
                                } else {
                                    $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Votre délai est expiré, vous ne pouvez plus définir de code"));
                                }
                                break;
                            case 0:
                                if (!$p->enterCodeWaitEnd >= time()) {
                                    self::createCode($p);
                                } else {
                                    $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Votre délai n'est pas encore expiré, vous ne pouvez pas encore crée(e)r de code"));
                                }
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Form::CODE_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Choisissez ce que vous voulez faire, vous pouvez créer un code de parrainage avec le grade §cVidéaste"));
            $form->setButton1("Entrer un code" . ($player->getSponsorCode() ? "\n§c§lIMPOSSIBLE" : ""));
            $form->setButton2("Crée(e)r un code");
            $player->sendForm($form);
        }
    }

    public static function enterCode(MPlayer $player)
    {
        $form = new CustomForm(
            function (MPlayer $p, $data) {
                if ($data !== null) {
                    if (Plugin::getInstance()->getCodes()->exists($data[1])) {
                        if (!$p->hasJoinedWithCode) {
                            if (Plugin::getInstance()->getCodes()->get($p->getName()) == $data[1]) {
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous ne pouvez pas entré votre propre code"));
                            }

                            $p->code = strtolower($data[1]);
                            $p->hasJoinedWithCode = true;
                            $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous avez entré le code {PRIMARY}{CODE}{SECONDARY} avec succès, mais vous ne pourrez plus en utiliser", ["{CODE}"], [$data[1]]));
                        } else {
                            $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous avez déjà entré un code, vous ne pouvez donc plus en utiliser"));
                        }
                    } else {
                        $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Le code {CODE} n'existe pas, veuillez réessayer", ["{CODE}"], [$data[1]]));
                    }
                }
            }
        );

        $form->setTitle(Form::CODE_TITLE);
        $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Mettez ici le code qu'on vous à donné"));
        $form->addInput("Code");
        $player->sendForm($form);
    }

    public static function createCode(MPlayer $player)
    {
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        $code = strtolower($data[1]);

                        $codes = Plugin::getInstance()->getCodes();
                        $codes->set($code, $p->getName());
                        $codes->save();

                        $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous venez de créer le code {PRIMARY}$code{SECONDARY}, à présent vous pouvez le donner au autres joueurs pour qu'ils recoivent {PRIMARY}100{ECONOMY_SYMBOL} {SECONDARY}et vous {PRIMARY}150{ECONOMY_SYMBOL}"));
                    }
                }
            );

            $form->setTitle(Form::CODE_TITLE);
            $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Mettez ici le code que vous voulez que le joueur précise pour recevoir de l'argent de départ, "));
            $form->addInput("Que voulez-vous définir comme code ?", "10 caractères maximums");
            $player->sendForm($form);
        }
    }
}
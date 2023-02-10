<?php

declare(strict_types=1);

namespace Binstellar\ContactResponse\Plugin\Frontend\Magento\Contact\Model;

use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Binstellar\ContactResponse\Helper\Email;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Mail
{
    /**
     * @var Email
     */
    private $helper;

    /**
     * @var ConfigInterface
     */
    private $contactsConfig;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    protected $scopeConfig;


    /**
     * @param \Binstellar\ContactResponse\Helper\Email $helper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $contactsConfig
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Email $helper,
        ConfigInterface $contactsConfig,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->helper = $helper;
        $this->contactsConfig = $contactsConfig;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Contact\Model\Mail $subject
     * @param [type] $result
     * @param array $variables
     * @param string $replyTo
     * @return void
     */
    public function afterSend(
        \Magento\Contact\Model\Mail $subject,
        $result,
        $variables,
        $replyTo
    ) {
        if (!$this->helper->getConfirmation()) {
            return;
        }

        /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
        $email = $this->scopeConfig->getValue('trans_email/ident_support/email',ScopeInterface::SCOPE_STORE);
        $name  = $this->scopeConfig->getValue('trans_email/ident_support/name',ScopeInterface::SCOPE_STORE);
        

        // $this->contactsConfig->emailSender(); string
        // $this->contactsConfig->emailRecipient(); email
        $replyToEmail = !empty($replyTo['data']['email']) ? $replyTo['data']['email'] : null;
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->helper->getConfirmationTemplate())
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars($replyTo)
                ->setFrom([
                    'email' => $this->contactsConfig->emailRecipient(),
                    'name' => $name
                ])
                ->addTo($replyToEmail)
                ->setReplyTo(
                    $this->contactsConfig->emailRecipient(),
                    $name
                )
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
        return $result;
    }
}

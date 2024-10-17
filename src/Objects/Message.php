<?php

namespace Rentceisy\PachcaBotSdk\Objects;


/**
 * Class Message.
 */
class Message extends BaseObject
{
    /**
     * @var string[]
     */
    protected const TYPES = [
        'text',
        'audio',
        'animation',
        'dice',
        'document',
        'game',
        'photo',
        'sticker',
        'video',
        'video_note',
        'voice',
        'contact',
        'location',
        'venue',
        'poll',
        'new_chat_member',
        'new_chat_members',
        'left_chat_member',
        'new_chat_title',
        'new_chat_photo',
        'delete_chat_photo',
        'group_chat_created',
        'supergroup_chat_created',
        'channel_chat_created',
        'migrate_to_chat_id',
        'migrate_from_chat_id',
        'pinned_message',
        'invoice',
        'successful_payment',
        'passport_data',
        'proximity_alert_triggered',
        'voice_chat_started',
        'voice_chat_ended',
        'voice_chat_participants_invited',
        'web_app_data',
    ];

    /**
     * {@inheritdoc}
     *
     * @return array{from: string, chat: string, forward_from: string, forward_from_chat: string, reply_to_message: class-string<Message>, entities: string[], caption_entities: string[], audio: string, dice: string, animation: string, document: string, game: string, photo: string[], sticker: string, video: string, voice: string, video_note: string, contact: string, location: string, venue: string, poll: string, new_chat_member: string, new_chat_members: string[], left_chat_member: string, new_chat_photo: string[], delete_chat_photo: string, pinned_message: class-string<Message>, invoice: string, successful_payment: string, passport_data: string, sender_chat: string, proximity_alert_triggered: string, voice_chat_started: string, voice_chat_ended: string, voice_chat_participants_invited: string, web_app_data: string}
     */
    public function relations(): array
    {
        return [

        ];
    }

    /**
     * Determine if the message is of given type.
     */
    public function isType(string $type): bool
    {
        if ($this->has(strtolower($type))) {
            return true;
        }

        return $this->detectType() === $type;
    }

    /**
     * Detect type based on properties.
     */
    public function objectType(): ?string
    {
        return $this->detectType();
    }

    /**
     * Detect type based on properties.
     *
     * @deprecated Will be removed in v4.0, please use {@see Message::objectType} instead.
     */
    public function detectType(): ?string
    {
        return $this->findType(static::TYPES);
    }

    /**
     * Does this message contain a command entity.
     */
    public function hasCommand(): bool
    {
        return $this->get('entities', collect())->contains('type', 'bot_command');
    }
}

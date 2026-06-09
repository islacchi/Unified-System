<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    // ── Helper ───────────────────────────────────────────────────────────────

    /**
     * Quick one-liner to write a log entry.
     *
     *  ActivityLog::log(
     *      action:     'rfq.status_changed',
     *      subject:    $rfq,
     *      oldValues:  ['status' => 'Received'],
     *      newValues:  ['status' => 'Awarded'],
     *      description: "Changed RFQ #{$rfq->rfq_number} from Received to Awarded"
     *  );
     */
    public static function log(
        string $action,
        Model|null $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
    ): static {
        return static::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
            'old_values'   => $oldValues,
            'new_values'   => $newValues,
            'description'  => $description ?? self::buildDescription($action, $subject, $oldValues, $newValues),
        ]);
    }

    /**
     * Auto-generate a human-readable description when one isn't provided.
     */
    protected static function buildDescription(
        string $action,
        ?Model $subject,
        ?array $old,
        ?array $new,
    ): string {
        $label = '';
        if ($subject instanceof Rfq) {
            $label = $subject->rfq_number;
        } elseif ($subject instanceof Agency) {
            $label = $subject->name;
        } elseif ($subject instanceof \App\Models\User) {
            $label = $subject->name;
        } elseif ($subject) {
            $label = '#' . $subject->id;
        }

        $oldStatus = is_array($old) && isset($old['status']) ? $old['status'] : '';
        $newStatus = is_array($new) && isset($new['status']) ? $new['status'] : '';
        $doc       = is_array($new) && isset($new['doc'])    ? $new['doc']    : '';

        $map = [
            'rfq.created'           => "Created new RFQ #{$label}",
            'rfq.updated'           => "Updated RFQ #{$label}",
            'rfq.deleted'           => "Deleted RFQ #{$label}",
            'rfq.status_changed'    => "Changed status of RFQ #{$label} from {$oldStatus} to {$newStatus}",
            'rfq.declined'          => "Declined RFQ #{$label}",
            'rfq.document_toggled'  => "Marked document \"{$doc}\" as " . ($new['checked'] ?? true ? 'received' : 'not received') . " on RFQ #{$label}",
            'rfq.document_date_set' => "Set document \"{$doc}\" date on RFQ #{$label}",
            'agency.created'        => "Added new agency \"{$label}\"",
            'agency.updated'        => "Updated agency \"{$label}\"",
            'agency.deleted'        => "Deleted agency \"{$label}\"",
            'user.created'          => "Added new user \"{$label}\"",
            'user.updated'          => "Updated user \"{$label}\"",
            'user.deleted'          => "Deleted user \"{$label}\"",
        ];

        return $map[$action] ?? ucfirst(str_replace('_', ' ', $action));
    }
}
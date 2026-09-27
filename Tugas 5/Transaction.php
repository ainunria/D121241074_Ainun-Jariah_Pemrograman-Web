<?php

class Transaction 
{
    // Constructor Property Promotion dengan properti private
    public function __construct(
        private string $id,
        private string $type,
        private float $amount
    ) {}

    public function getId(): string 
    {
        return $this->id;
    }

    public function getType(): string 
    {
        return $this->type;
    }

    public function getAmount(): float 
    {
        return $this->amount;
    }

    /**
     * Memproses transaksi berdasarkan tipe (deposit / withdrawal).
     * Saldo diupdate langsung ke array $_SESSION['balance'].
     */
    public function process(): bool 
    {
        if (!isset($_SESSION['balance'])) {
            $_SESSION['balance'] = 0.0;
        }

        // Ekspresi match untuk memproses tipe transaksi
        return match ($this->type) {
            'deposit' => $this->handleDeposit(),
            'withdrawal' => $this->handleWithdrawal(),
            default => false,
        };
    }

    private function handleDeposit(): bool 
    {
        $_SESSION['balance'] += $this->amount;
        return true;
    }

    private function handleWithdrawal(): bool 
    {
        // Tolak jika saldo tidak mencukupi
        if ($_SESSION['balance'] < $this->amount) {
            return false;
        }

        $_SESSION['balance'] -= $this->amount;
        return true;
    }
}